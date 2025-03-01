<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ActivityDetail;
use App\Models\ActivityDetailsFrequency;
use App\Http\Controllers\Api\SubActivityFrequencyController;
use App\Models\Activity;
use App\Models\ActivityAppointment;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class ActivityDetailController extends Controller
{
    //protected $subActivityFrequencyController;

    // public function __construct(SubActivityFrequencyController $subActivityFrequencyController)
    // {
    //     $this->subActivityFrequencyController = $subActivityFrequencyController;
    // }


    /*-------------------------add activity-details has frequencies:------------------------------------------ */

    protected function validateRequest(Request $request)
    {
        if (!$request->has('repeat_count_per_day')) {
            $request->merge([
                'repeat_count_per_day' => 1
            ]);
        }

        $validator = Validator::make($request->all(), [
            'sub_activity_name' => 'required|min:4|max:14',
            'sub_activity_type' => 'required|in:activity,measure,medical_appointment,medicine',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'every_x_day' => 'nullable|integer',
            'repeat_count_per_day' => 'required|integer|min:1',
            'start_time' => 'nullable|date_format:H:i',
            'every_x_hours' => 'nullable|integer|min:1',
            'repeat_times' => 'nullable|array',
            'repeat_times.*' => 'date_format:H:i'
        ]);

        // تحقق مخصص
        $validator->after(function ($validator) use ($request) {
            $data = $request->all();

            // تأكد من أن المستخدم قد أدخل إما every_x_hours أو repeat_times
            // if (empty($data['every_x_hours']) && empty($data['repeat_times'])) {
            //     $validator->errors()->add('repeat_parameters', 'You must enter either every_x_hours or repeat_times.');
            // }

            // منع إدخال every_x_hours و repeat_times معًا
            if (!empty($data['every_x_hours']) && !empty($data['repeat_times'])) {
                $validator->errors()->add('repeat_parameters', 'You cannot use both every_x_hours and repeat_times together.');
            }

            // إذا كان every_x_hours موجودًا، تأكد من وجود start_time
            if (!empty($data['every_x_hours']) && empty($data['start_time'])) {
                $validator->errors()->add('start_time', 'The start_time field is required when using every_x_hours.');
            }

            // إذا كانت repeat_times موجودة، لا داعي لإدخال start_time
            if (!empty($data['repeat_times'])) {
                $validator->sometimes('start_time', 'nullable', function () {
                    return true;
                });
            }
        });

        // التحقق من حجم repeat_times
        $validator->sometimes('repeat_times', 'size:' . $request->input('repeat_count_per_day'), function ($input) {
            return $input->repeat_count_per_day > 0 && $input->repeat_times;
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    }

    /**********************************************************/

    public function storeActivityDetail($activity_details)
    {
        try {
            return ActivityDetail::create([
                'sub_activity_name' => $activity_details['sub_activity_name'],
                'sub_activity_type' => $activity_details['sub_activity_type'],
                'start_date' => $activity_details['start_date'],
                'number_of_day' => $activity_details['number_of_day'],
                'end_date' => $activity_details['end_date'],
                'every_x_hours' => $activity_details['every_x_hours'],
                'every_x_day' => $activity_details['every_x_day'],
                'repeat_count_per_day' => $activity_details['repeat_count_per_day']
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('فشل في إنشاء النشاط الأساسي: ' . $e->getMessage());
        }
    }
    /*************************************************************** */
    public function storeActivityDetails(Request $request)
    {
        // التحقق من صحة البيانات المدخلة أولاً
        $validationResult = $this->validateRequest($request);
        if ($validationResult) {
            return $validationResult;
        }
        // بدء المعاملة مع قاعدة البيانات
        DB::beginTransaction();

        try {
            $group_id = $request->input('group_id');
            $activity_id = $request->input('activity_id');
            $activity_details = [
                'sub_activity_name' => $request->input('sub_activity_name'),
                'sub_activity_type' => $request->input('sub_activity_type'),
                'start_date' => $request->input('start_date'),
                'number_of_day' => $request->input('number_of_day'),
                'end_date' => $this->calculateEndDate($request),
                'every_x_day' => $request->input('every_x_day'),
                'days_of_week' => $request->input('days_of_week', []),
                'start_time' => $request->input('start_time'),
                'repeat_times' => $request->input('repeat_times', []),
                'every_x_hours' => $request->input('every_x_hours'),
                'repeat_count_per_day' => $request->input('repeat_count_per_day'),
            ];

            // انشاء النشاط الأساسي
            $activity_detail = $this->storeActivityDetail($activity_details);
            // dd($activity_detail);

            // انشاء تواتر النشاط (المواعيد المرتبطة)
            $this->addActivityDetailAppointments(
                $activity_detail,
                $group_id,
                $activity_details,
                $activity_id
            );
            // dd($activity_detail);

            // تأكيد المعاملة إذا نجحت جميع العمليات
            DB::commit();

            return response()->json([
                'message' => 'Activities stored successfully',
                'data' => $activity_detail
            ], 201);
        } catch (\Exception $e) {
            // التراجع عن جميع العمليات في حالة الخطأ
            DB::rollBack();

            return response()->json([
                'message' => 'Failed to store activities',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /*********************************************************/
    protected function addActivityDetailAppointments($activity_detail, $group_id, $activity_details, $activity_id)
    {
        try {
            $startDate = Carbon::parse($activity_details['start_date']);
            $endDate = Carbon::parse($activity_details['end_date']);
            //dd($startDate);
            //dd($endDate);
            // جلب جميع المواعيد للفترة الزمنية المحددة
            //ممكن تكون بس اذا مافي انشطة مرتبطة بمواعيد وبالتالي كانه مافي مواعيد من اصله 

            $appointments = $this->getAppointmentsByActivities($group_id, $activity_id, $startDate, $endDate);

            if ($appointments->isEmpty()) {
                throw new \RuntimeException('No appointments found for the specified criteria.');
            }
            // dd(isset($activity_details['number_of_day']));
            //
            // dd( $activity_details['number_of_day'] == 1&& $activity_details['repeat_count_per_day'] == 1 );
            // dd($appointments);
            // التحقق من الشروط وإلا رمي استثناء
            if (isset($activity_details['number_of_day']) && $activity_details['repeat_count_per_day'] >= 2) {
                //dd($activity_details);
                $this->createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details);
            } elseif (isset($activity_details['every_x_day'])) {
                $filtered_appointments = $this->getAppointmentsByGroupIdAndEveryXDay($appointments, $startDate, $activity_details['every_x_day']);
                $this->createDailyRepetitions($filtered_appointments, $activity_detail, $group_id, $activity_details);
            } elseif (!empty($activity_details['days_of_week'])) {
                $filtered_appointments = $this->getAppointmentsBySpecificDaysOfWeek($appointments, $activity_details['days_of_week']);
                $this->createDailyRepetitions($filtered_appointments, $activity_detail, $group_id, $activity_details);
            } elseif (isset($activity_details['number_of_day']) && $activity_details['number_of_day'] == 1 && $activity_details['repeat_count_per_day'] == 1) {
                foreach ($appointments as $appointment_data) {
                    foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
                        $this->createAppointment($activity_detail, $activity_details, $activity_appointment_id);
                    }
                }
            } else {
                throw new \RuntimeException('Invalid activity configuration.');
            }
        } catch (\Exception $e) {
            throw new \RuntimeException('فشل في إنشاء التواتر: ' . $e->getMessage());
        }
    }

    /*********************************************/
    protected function getAppointmentsByGroupIdAndEveryXDay($appointments, $startDate, $every_x_day)
    {
        //dd($appointments);
        // مصفوفة لتخزين المواعيد حسب كل X يوم
        $filtered_appointments = [];
        // dd($startDate);
        // $startDate = Carbon::parse($start_date);
        foreach ($appointments as $appointment_data) {
            // الوصول إلى بيانات الموعد
            // dd($appointment_data);
            $appointment = $appointment_data['appointment'];
            // dd($appointment);
            // كل المواعيد من بعد تاريخ البداية
            $appointment_date = Carbon::parse($appointment->appointment_date);
            // dd($appointment_date);
            // إذا كان الفرق بين تواريخ المواعيد يمثل الفاصل الزمني المطلوب
            if ($appointment_date->diffInDays($startDate) % $every_x_day == 0) {
                //element of $appointments($appointments['appointment']+appointmentIds)
                $filtered_appointments[] = $appointment_data;
            }
        }
        // dd($filtered_appointments);
        return $filtered_appointments;
    }
    /****************************************/
    protected function getAppointmentsBySpecificDaysOfWeek($appointments, $days_of_week)
    {
        //  dd($appointments);
        // مصفوفة لتخزين المواعيد حسب الأيام المحددة
        $filtered_appointments = [];
        // dd($$filtered_appointments);

        foreach ($appointments as $appointment_data) {
            // الوصول إلى بيانات الموعد
            //dd($appointment_data);
            $appointment = $appointment_data['appointment'];
            // dd($appointment);

            // إذا كان اليوم للموعد موجود ضمن مجموعة الأيام المحددة
            if (in_array($appointment->day_name, $days_of_week)) {
                // إضافة العنصر إلى المصفوفة المفلترة
                $filtered_appointments[] = $appointment_data;
            }
        }
        //   dd($filtered_appointments);
        return $filtered_appointments;
    }


    /**********************************/
    protected function createAppointment($activity_detail, $activity_details, $activity_appointment_id)
    {
        try {
            ActivityDetailsFrequency::create([
                'activity_detail_id' => $activity_detail->id,
                'activity_appointment_id' => $activity_appointment_id,
                'start_time' => $activity_details['start_time'],
                'status' => 'not_completed'
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('فشل في إنشاء تواتر للنشاط: ' . $e->getMessage());
        }
    }



    /******************************************/
    protected function calculateEndDate(Request $request)
    {
        if ($request->filled('end_date')) {
            return $request->input('end_date');
        }

        if ($request->filled('number_of_day')) {
            return Carbon::parse($request->input('start_date'))
                ->addDays($request->input('number_of_day') - 1) // ناقص 1 لاحتساب اليوم الأول
                ->toDateString();
        }

        $lastAppointmentDate = $this->getLastAppointmentDate($request->input('group_id'));
        //من المستحيل تاريخ الانتهاء يكون فاضي  
        //اذا كان فاضي بتم اسناد اليه قيمة تاريخ البداية
        return $lastAppointmentDate?->toDateString() ?? $request->input('start_date');
    }
    /***************************************/
    protected function getLastAppointmentDate($group_id)
    {
        $lastAppointment = Appointment::where('group_id', $group_id)
            ->orderByDesc('appointment_date')
            ->first();
        //  dd($lastAppointment);
        return $lastAppointment ? Carbon::parse($lastAppointment->appointment_date) : null;
    }
    /********************************************/

    public function getAppointmentsByActivities($group_id, $activity_id, $start_date, $end_date)
    {
        $appointments = Appointment::where('group_id', $group_id)
            ->where('appointment_date', '>=', $start_date)
            ->whereHas('activities', function ($query) use ($activity_id) {
                $query->where('activities.id', $activity_id);
            })
            ->when($end_date, function ($query) use ($end_date) {
                return $query->where('appointment_date', '<=', $end_date);
            })
            ->with(['activities' => function ($query) use ($activity_id) {
                $query->where('activity_id', $activity_id)
                    ->select('activities.id')
                    ->withPivot('id');
            }])
            ->orderBy('appointment_date')
            ->get()
            ->map(function ($appointment) {

                return [
                    'appointment' => $appointment->makeHidden('activities'),
                    'activity_appointment_ids' => $appointment->activities->pluck('pivot.id')
                ];
            });
        // dd($appointments);
        return $appointments;
    }
    /****************************************************/
    protected function createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details)
    {
        $initial_start_time = $activity_details['start_time'];
        // dd($initial_start_time);
        foreach ($appointments as $appointment_data) {
            //$appointment = $appointment_data['appointment'];
            // dd($appointment_data);
            // معالجة الأوقات المكررة
            if (!empty($activity_details['repeat_times'])) {
                $this->processRepeatTimes($appointment_data, $activity_detail, $activity_details);
            }
            // معالجة الفاصل الزمني بالساعات
            else if (!empty($activity_details['every_x_hours'])) {
                $this->processEveryXHours($activity_details, $appointment_data, $activity_detail, $initial_start_time);
            }
        }
    }
    /***********************************************/
    protected function processRepeatTimes($appointment_data, $activity_detail, $activity_details)
    {
        foreach ($activity_details['repeat_times'] as $time) {
            $activity_details['start_time'] = $time;
            //echo  $activity_details['start_time'] . "\n";
            foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
                //echo  $activity_appointment_id . "\n";
                $this->createAppointment($activity_detail, $activity_details, $activity_appointment_id);
            }
        }
        //dd($activity_details['start_time']);
    }
    /********************************************/
    protected function processEveryXHours($activity_details, $appointment_data, $activity_detail, $initial_start_time)
    {
        $every_x_hours = $activity_details['every_x_hours'];

        for ($i = 0; $i < $activity_details['repeat_count_per_day']; $i++) {
            $activity_details['start_time'] = Carbon::parse($initial_start_time)->addHours($i * $every_x_hours)->format('H:i');

            foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
                $this->createAppointment($activity_detail, $activity_details, $activity_appointment_id);
            }
        }
    }
    /*------------------------------------------------------------------------------- */

    /*-------------------------  // Retrieve detailed activities:------------------------------------------ */

    // public function getDetailedActivitiesByGroupId(Request $request)
    // {
    //     try {
    //         // 1. التحقق من التوكن واستخراج group_id
    //         // $token = JWTAuth::parseToken();
    //         // $payload = $token->getPayload();
    //         // $group_id = $payload->get('group_id');
    //         $group_id = $request->group_id;

    //         // 2. جلب المواعيد مع العلاقات
    //         $appointments = Appointment::with([
    //             'activities.activityAppointments.activityDetails' => function ($query) {
    //                 $query->select('activity_details.*');
    //             }
    //         ])->where('group_id', $group_id)->get();
    //         dd($appointments);
    //         // 3. التحقق من وجود المواعيد
    //         if ($appointments->isEmpty()) {
    //             return response()->json(['message' => 'No appointments found'], 404);
    //         }

    //         // 4. استخراج التفاصيل
    //         $activityDetails = $appointments->flatMap(function ($appointment) {
    //             return $appointment->activities->flatMap(function ($activity) {
    //                 return $activity->activityAppointments->flatMap(function ($activityAppointment) {
    //                     return $activityAppointment->activityDetails->unique('id');
    //                 });
    //             });
    //         })->unique('id');

    //         // 5. تنسيق النتيجة
    //         $filteredDetails = $activityDetails->map(function ($detail) {
    //             return [
    //                 'id' => $detail->id,
    //                 'sub_activity_name' => $detail->sub_activity_name,
    //                 'sub_activity_type' => $detail->sub_activity_type,
    //                 'start_date' => $detail->start_date,
    //                 'end_date' => $detail->end_date
    //             ];
    //         });

    //         return response()->json($filteredDetails);
    //     } catch (JWTException $e) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Invalid or missing token'
    //         ], 401);
    //     }
    // }

    public function getDetailedActivities(Request $request)
    {
        // الحصول على group_id (يمكن استخدام الطريقة التي تفضلها)
        $group_id = $request->group_id;
    
        // استعلام واحد مع العلاقات والتصفية
        $activityDetails = ActivityDetail::whereHas('activityDetailsFrequencies.activityAppointment.appointment', function($query) use ($group_id) {
                $query->where('group_id', $group_id);
            })
            ->with(['activityDetailsFrequencies' => function($query) {
                $query->with('activityAppointment.appointment');
            }])
            ->get();
    
        // إذا لم توجد نتائج
        if ($activityDetails->isEmpty()) {
            return response()->json(['message' => 'No activities found'], 404);
        }
    
        // تنسيق النتيجة
        $filteredDetails = $activityDetails->map(function ($detail) {
            return [
                'id' => $detail->id,
                'sub_activity_name' => $detail->sub_activity_name,
                'sub_activity_type' => $detail->sub_activity_type,
                'start_date' => $detail->start_date,
                'end_date' => $detail->end_date,
                'frequencies' => $detail->activityDetailsFrequencies->map(function ($frequency) {
                    return [
                        'start_time' => $frequency->start_time,
                        'status' => $frequency->status,
                        'appointment_date' => $frequency->activityAppointment->appointment->appointment_date
                    ];
                })
            ];
        });
    
        return response()->json($filteredDetails);
    }
 /*------------------------------------------------------------------------------- */

    /*-------------------------  // ارجاع تواريخ المواعيد:------------------------------------------ */


    // Retrieve appointment dates by group_id
    public function getAppointmentDates(Request $request)
    {
       
        if (!$request->has('group_id')) {
            return response()->json(['message' => 'group_id parameter is required'], 400);
        }

        $group_id = $request->input('group_id');

        
        if (!is_numeric($group_id)) {
            return response()->json(['message' => 'Invalid group_id format'], 400);
        }

        $appointments = Appointment::where('group_id', $group_id)
            ->get(['appointment_date']);

        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'No appointments found'], 404);
        }

        return response()->json($appointments);
    }

 /*------------------------------------------------------------------------------- */

    /*-------------------------  // تخزين نشاط لمرة واحدو وليوم واحد  :------------------------------------------ */

    public function createSingleDayActivity(Request $request)
    {
        DB::beginTransaction();
        
        try {
            // 1. استخدام دالة التحقق الموجودة
            $validationResult = $this->validateRequest($request);
            if ($validationResult) {
                return $validationResult;
            }
    
            // 2. إعداد بيانات إضافية
            $request->merge([
                'group_id' => $request->input('group_id'),
                'start_date' => Carbon::today(),
                'end_date' => Carbon::today(),
                'repeat_count_per_day' => 1,
                'number_of_day' => 1
            ]);
    
            // 3. إنشاء النشاط الأساسي باستخدام الدالة الأصلية
            $activityDetail = $this->storeActivityDetail($request->all());
    
            // 4. معالجة الصورة بشكل منفصل
            $imagePath = $request->file('sub_activity_image')->store(
                'activity_images', 
                'public'
            );
            $activityDetail->update(['sub_activity_image' => $imagePath]);
    
            // 5. استخدام الدالة الأصلية لإدارة التكرارات
            $this->addActivityDetailAppointments(
                $activityDetail,
                $request->input('group_id'),
                $request->all(),
                $request->input('activity_id')
            );
    
            DB::commit();
            
            return response()->json([
                'status' => 'success',
                'message' => 'تم الإنشاء بنجاح',
                'data' => [
                    'activity_detail' => $activityDetail,
                    'image_url' => asset("storage/$imagePath")
                ]
            ], 201);
    
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'حدث خطأ أثناء الإنشاء',
                'error' => $e->getMessage()
            ], 500);
        }
    
    }

















    }
    
