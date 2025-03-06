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
use App\Traits\FrequencyValidationTrait;
   

class ActivityDetailController extends Controller
{
    use FrequencyValidationTrait;

    /*-------------------------add sub-activity with frequencies:------------------------------------------ */

    /*-----------------------------validation--------------------------------- */
    //عملية التحقق:
    protected function validateRequest(Request $request)
    {
        if (!$request->has('repeat_count_per_day')) {
            $request->merge(['repeat_count_per_day' => 1]);
        }

        // 1. حساب end_date أولًا قبل أي تحقق فرعي
        $validator = Validator::make($request->all(), [
            'sub_activity_name' => 'required|min:4|max:14',
            'sub_activity_type' => 'required|in:activity,measure,medical_appointment,medicine',
            'frequencies_time' => 'required|in:every_x_day,number_of_day,day_of_week,once_time',
            'start_date' => $this->startDateRules($request),
            'repeat_count_per_day' => 'required|integer|min:1'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 2. حساب end_date هنا قبل استدعاء الدوال الفرعية
        $endDate = $this->calculateEndDate($request, $validator);
        $request->merge(['end_date' => $endDate]);

        // 3. التحقق النهائي من end_date
        $validator->after(function ($validator) use ($request) {
            if ($request->has('end_date')) {
                $this->validateEndDateExists($validator, $request);
            }
        });

        // 4. الآن نستدعي التحقق حسب نوع التواتر
        $result = $this->validateByFrequencyType($validator, $request);
        if ($result) {
            return $result;
        }

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        return null;
    }
    /*-------------------------الدالة الرئيسية(Api)-------------------------- */

    public function storeSubActivity(Request $request)
    {
        // استدعاء لدالة ال validation 
        $validationResult = $this->validateRequest($request);
        if ($validationResult) {
            return $validationResult;
        }
        DB::beginTransaction();
        try {

            $group_id = $request->input('group_id');
            // dd($group_id);
            $activity_id = $request->input('activity_id');
            // dd($activity_id);
            $activity_details = [
                'sub_activity_name' => $request->input('sub_activity_name'),
                'sub_activity_type' => $request->input('sub_activity_type'),
                'start_date' => $request->input('start_date'),
                'number_of_day' => $request->input('number_of_day'),
                'end_date' => $request->end_date,
                'every_x_day' => $request->input('every_x_day'),
                'days_of_week' => $request->input('days_of_week', []),
                'start_time' => $request->input('start_time'),
                'repeat_times' => $request->input('repeat_times', []),
                'every_x_hours' => $request->input('every_x_hours'),
                'repeat_count_per_day' => $request->input('repeat_count_per_day'),
            ];

            // انشاء النشاط الأساسي
            $activity_detail = $this->storeActivityDetail($activity_details);
            //  dd($activity_detail);

            $appointmentResult = $this->addActivityDetailAppointments(
                $activity_detail,
                $request->group_id,
                $activity_details,
                $request->activity_id
            );

            if ($appointmentResult && $appointmentResult->getStatusCode() !== 201) {
                DB::rollBack();
                return $appointmentResult;
            }
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
    /*---------------------- create_sub_activity:-----------------*/

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
    /*---------------------- create_sub_activity:-----------------*/
    protected function addActivityDetailAppointments($activity_detail, $group_id, $activity_details, $activity_id)
    {
        try {
            $startDate = Carbon::parse($activity_details['start_date']);
            $endDate = Carbon::parse($activity_details['end_date']);
            // dd($startDate);
            // dd($endDate);
            // جلب جميع المواعيد للفترة الزمنية المحددة
            //ممكن تكون بس اذا مافي انشطة مرتبطة بمواعيد وبالتالي كانه مافي مواعيد من اصله 

            $appointments = $this->getAppointmentsByActivities($group_id, $activity_id, $startDate, $endDate);
            // dd($appointments);
            if ($appointments->isEmpty()) {
                return response()->json([
                    'message' => 'لا توجد مواعيد في الفترة المحددة'
                ], 404); // رمز حالة 404 للغير موجود
            }

            // dd(isset($activity_details['number_of_day']));
            //
            // dd( $activity_details['number_of_day'] == 1&& $activity_details['repeat_count_per_day'] == 1 );
            // dd($appointments);
            // التحقق من الشروط وإلا رمي استثناء
            if (isset($activity_details['number_of_day']) && $activity_details['repeat_count_per_day'] >= 2) {
                // dd($activity_details);
                if ($activity_details['repeat_count_per_day'] == 1) {
                }
                $this->createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details);
            } elseif (isset($activity_details['every_x_day'])) {
                // if (empty($activity_details['every_x_day'])) {
                //     return response()->json([
                //         'message' => 'حقل every_x_day مطلوب'
                //     ], 422);
                // }
                $filtered_appointments = $this->getAppointmentsByGroupIdAndEveryXDay($appointments, $startDate, $activity_details['every_x_day']);
                //    dd($filtered_appointments);
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
                return response()->json([
                    'message' => 'تكوين النشاط غير صحيح'
                ], 400); // رمز حالة 400 لطلب خاطئ
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'فشل في إنشاء التواتر',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /*----------------------------انشاء تواتر على اليوم--------------------*/
    protected function createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details)
    {
        $initial_start_time = $activity_details['start_time'];
        // dd($initial_start_time);
        foreach ($appointments as $appointment_data) {
            $appointment = $appointment_data['appointment'];
            //  dd($appointment_data);
            // dd($appointment);
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
                'day_name' => '',
                'sub_activity_date' => '',
                'start_time' => $activity_details['start_time'],
                'status' => 'not_completed'
            ]);
        } catch (\Exception $e) {
            throw new \RuntimeException('فشل في إنشاء تواتر للنشاط: ' . $e->getMessage());
        }
    }



    /******************************************/

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
        return Appointment::where('group_id', $group_id)
            ->whereBetween('appointment_date', [$start_date, $end_date])
            ->whereHas('activities', function ($query) use ($activity_id) {
                $query->where('activities.id', $activity_id);
            })
            ->with(['activities' => function ($query) use ($activity_id) {
                $query->where('activity_id', $activity_id)
                    ->select('activities.id')
                    ->withPivot('id');
            }])
            ->get()
            ->map(function ($appointment) {
                return [
                    'appointment' => $appointment->makeHidden('activities'),
                    'activity_appointment_ids' => $appointment->activities->pluck('pivot.id')
                ];
            });
    }
    /****************************************************/

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
                //echo  $activity_appointment_id . "\n";
                $this->createAppointment($activity_detail, $activity_details, $activity_appointment_id);
            }
        }
    }



















    /*------------------------------------------------------------------------------- */

    /*-------------------------  // Retrieve sub-activities:------------------------------------------ */

    public function getDetailedActivities(Request $request)
    {
        try {
            // استخراج التوكن من الطلب
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['error' => 'يجب إرسال التوكن'], 401);
            }
    
            // فك تشفير التوكن للحصول على الـ claims
            $payload = JWTAuth::setToken($token)->getPayload();
            $claims = $payload->toArray();
    
            // التحقق من وجود group_id أو appointment_ids في التوكن
            if (empty($claims['group_id']) && empty($claims['appointment_ids'])) {
                return response()->json(['error' => 'التوكن لا يحتوي على بيانات كافية'], 400);
            }
    
            $resolved_group_id = null;
            $appointment_ids = [];
    
            // تحديد group_id من التوكن
            if (!empty($claims['group_id'])) {
                $resolved_group_id = $claims['group_id'];
                $appointment_ids = $claims['appointment_ids'] ?? [];
            } else {
                // استخدام أول appointment_id في التوكن لاستخراج group_id
                $appointment_id = $claims['appointment_ids'][0] ?? null;
                if (!$appointment_id) {
                    return response()->json(['error' => 'لا توجد مواعيد في التوكن'], 400);
                }
    
                $appointment = Appointment::find($appointment_id);
                if (!$appointment) {
                    return response()->json(['error' => 'الموعد غير موجود'], 404);
                }
    
                $resolved_group_id = $appointment->group_id;
                $appointment_ids = [$appointment_id];
            }
    
            // التحقق من صحة group_id
            $groupExists = Appointment::where('group_id', $resolved_group_id)->exists();
            if (!$groupExists) {
                return response()->json(['error' => 'المجموعة غير موجودة'], 404);
            }
    
            // بناء الاستعلام بناءً على group_id
            $activityDetails = ActivityDetail::whereHas(
                'activityDetailsFrequencies.activityAppointment.appointment',
                function ($query) use ($resolved_group_id) {
                    $query->where('group_id', $resolved_group_id);
                }
            )
            ->with(['activityDetailsFrequencies.activityAppointment.appointment'])
            ->get();
    
            // إذا لم توجد نتائج
            if ($activityDetails->isEmpty()) {
                return response()->json(['message' => 'لا توجد أنشطة'], 404);
            }
    
            // بناء البيانات المرسلة
            $responseData = $activityDetails->map(function ($detail) {
                return [
                    'id' => $detail->id,
                    'sub_activity_name' => $detail->sub_activity_name,
                    'sub_activity_type' => $detail->sub_activity_type,
                    'start_date' => $detail->start_date,
                    'end_date' => $detail->end_date,
                    'frequencies' => $detail->activityDetailsFrequencies->map(function ($freq) {
                        return [
                            'start_time' => $freq->start_time,
                            'status' => $freq->status,
                            'appointment_date' => $freq->activityAppointment->appointment->appointment_date,
                        ];
                    })
                ];
            });
    
            return response()->json([
                'data' => $responseData,
            ]);
    
        } catch (\Exception $e) {
            return response()->json(['error' => 'رمز الوصول غير صالح'], 401);
        }
    }

//     /*------------------------------------------------------------------------------- */
    public function getAppointmentDates(Request $request)
    {
        try {
            // استخراج التوكن من الطلب
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'يجب إرسال التوكن'], 401);
            }
    
            // فك تشفير التوكن للحصول على الـ claims
            $payload = JWTAuth::setToken($token)->getPayload();
            $claims = $payload->toArray();
    
            $group_id = null;
    
            // الحالة 1: استخراج group_id مباشرةً من التوكن
            if (isset($claims['group_id']) && !empty($claims['group_id'])) {
                $group_id = $claims['group_id'];
            }
            // الحالة 2: استخراج group_id من appointment_id الموجود في التوكن
            else if (isset($claims['appointment_ids']) && !empty($claims['appointment_ids'])) {
                $appointment_id = $claims['appointment_ids'][0]; // نأخذ أول appointment_id
                $appointment = Appointment::find($appointment_id);
                
                if (!$appointment) {
                    return response()->json(['message' => 'appointment_id غير صحيح'], 404);
                }
                
                $group_id = $appointment->group_id;
            }
            // الحالة 3: لا يوجد بيانات كافية في التوكن
            else {
                return response()->json(
                    ['message' => 'التوكن يجب أن يحتوي إما group_id أو appointment_ids'],
                    400
                );
            }
    
            // البحث عن المواعيد باستخدام group_id المستخرج
            $appointments = Appointment::where('group_id', $group_id)
                ->whereDate('appointment_date', '>=', Carbon::today())
                ->get(['id', 'appointment_date']); // إضافة id للتوضيح
    
            if ($appointments->isEmpty()) {
                return response()->json(['message' => 'لا توجد مواعيد مستقبلية'], 404);
            }
    
            return response()->json([
                'group_id' => $group_id,
                'appointments' => $appointments
            ]);
    
        } catch (\Exception $e) {
            return response()->json(
                ['error' => 'رمز الوصول غير صالح: ' . $e->getMessage()],
                401
            );
        }
    }

    /*------------------------------------------------------------------------------- */

    /*-------------------------  // تخزين نشاط لمرة واحد وليوم واحد  :------------------------------------------ */

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
