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

class ActivityDetailController extends Controller
{
    //protected $subActivityFrequencyController;

    // public function __construct(SubActivityFrequencyController $subActivityFrequencyController)
    // {
    //     $this->subActivityFrequencyController = $subActivityFrequencyController;
    // }

 // protected function validateRequest(Request $request)
    // {
    //     $validator = Validator::make($request->all(), [
    //         'sub_activity_name' => 'required|min:4|max:14',
    //         'sub_activity_type' => 'required|in:activity,measure,medical_appointment,medicine',
    //         'start_date' => 'required|date',
    //         'end_date' => 'date|after:start_date',
    //         'user_comment' => 'min:5|max:60',
    //         'every_x_day' => 'nullable|integer',
    //         'repeat_count_per_day' => 'nullable|integer|min:1',
    //         'sub_activity_image' => 'nullable|image|mimes:png,jpg,jpeg,gif,svg|max:1024',
    //         'start_time' => 'nullable|date_format:H:i', // إضافة قاعدة أساسية
    //         'every_x_hours' => 'nullable|integer|min:1',
    //         'repeat_times' => 'nullable|array',
    //     ]);

    //     // ?????????????????????????? القواعد المشروطة ??????????????????????????
    //     $validator->sometimes(['every_x_hours', 'repeat_times'], 'required_without_all:every_x_hours,repeat_times', function ($input) {
    //         return $input->repeat_count_per_day > 0;
    //     });

    //     $validator->sometimes('repeat_times', 'size:' . $request->input('repeat_count_per_day'), function ($input) {
    //         return $input->repeat_count_per_day > 0 && $input->repeat_times;
    //     });

    //     $validator->sometimes('start_time', 'required', function ($input) {
    //         return $input->repeat_count_per_day > 0;
    //     });

    //     // ?????????????????????????? تحقق مخصص ??????????????????????????
    //     $validator->after(function ($validator) use ($request) {
    //         $data = $request->all();

    //         if (!empty($data['repeat_count_per_day'])) {
    //             // منع إدخال every_x_hours و repeat_times معًا
    //             if (!empty($data['every_x_hours']) && !empty($data['repeat_times'])) {
    //                 $validator->errors()->add('every_x_hours', 'لا يمكن استخدام كل من every_x_hours و repeat_times معًا.');
    //                 $validator->errors()->add('repeat_times', 'لا يمكن استخدام كل من every_x_hours و repeat_times معًا.');
    //             }

    //             // إذا كان every_x_hours موجودًا، تأكد من وجود start_time
    //             if (!empty($data['every_x_hours']) && empty($data['start_time'])) {
    //                 $validator->errors()->add('start_time', 'حقل start_time مطلوب عند استخدام every_x_hours.');
    //             }
    //         } else {
    //             // إذا لم يكن هناك تكرار يومي، start_time مطلوب دائمًا
    //             if (empty($data['start_time'])) {
    //                 $validator->errors()->add('start_time', 'حقل start_time مطلوب.');
    //             }
    //         }
    //     });

    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }
    // }






    
    public function storeActivityDetail($activity_details)
    {
        $activity_detail = ActivityDetail::create([
            'sub_activity_name' => $activity_details['sub_activity_name'],
            'sub_activity_type' => $activity_details['sub_activity_type'],
            'start_date' => $activity_details['start_date'],
            'number_of_day' => $activity_details['number_of_day'],
            'end_date' => $activity_details['end_date'],
            'every_x_hours' => $activity_details['every_x_hours'],
            'user_comment' => $activity_details['user_comment'],
            'every_x_day' => $activity_details['every_x_day'],
            'repeat_count_per_day' => $activity_details['repeat_count_per_day']
        ]);

        return $activity_detail;
    }

    public function storeActivities(Request $request)
    {
        $group_id = $request->input('group_id');
        $activity_id = $request->input('activity_id');
        $activity_details = [
            'sub_activity_name' => $request->input('sub_activity_name'),
            'sub_activity_type' => $request->input('sub_activity_type'),
            'start_date' => $request->input('start_date'),
            'number_of_day' => $request->input('number_of_day'),
            'end_date' => $this->calculateEndDate($request),
            'user_comment' => $request->input('user_comment'),
            'every_x_day' => $request->input('every_x_day'),
            'start_time' => $request->input('start_time'),
            'repeat_times' => $request->input('repeat_times', []),
            'every_x_hours' => $request->input('every_x_hours'),
            'sub_activity_execution_time' => $request->input('sub_activity_execution_time'),
            'value' => $request->input('value'),
            'provider_comment' => $request->input('provider_comment'),
            'repeat_count_per_day' => $request->input('repeat_count_per_day'),
            'sub_activity_image' => $request->input('sub_activity_image'),
            'is_caregiver' => $request->input('is_caregiver', false)
        ];
        //انشاء نشاط اساسي
        $activity_detail = $this->storeActivityDetail($activity_details);
        //  dd($activity_detail);
        //انشاء تواتر للنشاط
        $this->addActivityDetailAppointments($activity_detail, $group_id, $activity_details, $activity_id);
        // dd($activity_detail);
        // return response()->json(['message' => 'Activities stored successfully', 'data' => $activity_detail], 201);
    }
    protected function addActivityDetailAppointments($activity_detail, $group_id, $activity_details,  $activity_id)
    {
        //تم انشاء كائن زمني من كاربون
        $startDate = Carbon::parse($activity_details['start_date']);
        $endDate = Carbon::parse($activity_details['end_date']);
        //dd($startDate);
        //dd($endDate);
        // جلب جميع المواعيد للفترة الزمنية المحددة
        //ممكن تكون بس اذا مافي انشطة مرتبطة بمواعيد وبالتالي كانه مافي مواعيد من اصله 
        $appointments = $this->getAppointmentsByActivities($group_id, $activity_id, $startDate, $endDate);
        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'No appointments found for the specified criteria.']);
        }
        // dd($appointments);
        if (isset($activity_details['number_of_day'])) {
            $this->processAppointments($appointments, $activity_detail, $group_id, $activity_details, $activity_id);
        } elseif (isset($activity_details['every_x_day'])) {
            // فلترنا المواعيد لكل كل (ج) يوم
            $filtered_appointments = $this->getAppointmentsByGroupIdAndEveryXDay($appointments, $group_id, $startDate, $endDate, $activity_details['every_x_day']);
            // شفنا التواتر على اليوم
            $this->processAppointments($filtered_appointments, $activity_detail, $group_id, $activity_details, $activity_id);
        } elseif (isset($activity_details['days_of_week'])) {
            $appointments = $this->getAppointmentsBySpecificDaysOfWeek($appointments, $activity_detail, $group_id, $activity_details,  $startDate, $endDate, $activity_details['days_of_week']);
            $this->processAppointments($appointments, $activity_detail, $group_id, $activity_details, $activity_id);
        } else {
            // $this->createDailyAppointments($activity_detail, $group_id, $activity_details, $is_caregiver, $startDate, $endDate);
        }
    }

    // protected function processAppointments($appointments, $activity_detail, $group_id, $activity_details, $is_caregiver, $activity_id)
    // {
    //     if (isset($activity_details['repeat_count_per_day'])) {
    //         $this->createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details, $is_caregiver, $activity_id);
    //     } else {
    //         foreach ($appointments as $appointment) {
    //             $this->createAppointment($activity_detail, $group_id, $activity_details, $is_caregiver, $appointment);
    //         }
    //     }
    // }
    protected function processAppointments($appointments, $activity_detail, $group_id, $activity_details, $activity_id)
    {
        if (isset($activity_details['repeat_count_per_day'])) {
            $this->createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details, $activity_id);
        } else {
            foreach ($appointments as $appointment_data) {
                $appointment = $appointment_data['appointment'];
                foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
                    $this->createAppointment($activity_detail, $activity_details, $appointment, $activity_appointment_id);
                }
            }
        }
    }


    /*********************************************/
    protected function getAppointmentsByGroupIdAndEveryXDay($appointments, $group_id, $startDate, $end_date, $every_x_day)
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



    // protected function createAppointment($activity_detail, $activity_details, $appointment, $activity_appointment_id)
    // {
    //     ActivityDetailsFrequency::create([
    //         'activity_detail_id' => $activity_detail->id,
    //         'activity_appointment_id' => $activity_appointment_id,
    //         'start_time' => $activity_details['start_time'],
    //         'sub_activity_execution_time' => $activity_details['is_caregiver'] ? $activity_details['sub_activity_execution_time'] ?? null : null,
    //         'value' => $activity_details['is_caregiver'] ? $activity_details['value'] ?? null : null,
    //         'provider_comment' => $activity_details['is_caregiver'] ? $activity_details['provider_comment'] ?? null : null,
    //         'sub_activity_image' => $activity_details['is_caregiver'] ? $activity_details['sub_activity_image'] ?? null : null,
    //         'status' => $activity_details['is_caregiver'] ? 'completed' : 'not_completed',
    //     ]);
    // }
    protected function createAppointment($activity_detail, $activity_details, $appointment, $activity_appointment_id)
    {
        ActivityDetailsFrequency::create([
            'activity_detail_id' => $activity_detail->id,
            'activity_appointment_id' => $activity_appointment_id,
            'start_time' => $activity_details['start_time'],
            'sub_activity_execution_time' => $activity_details['is_caregiver'] ? $activity_details['sub_activity_execution_time'] ?? null : null,
            'value' => $activity_details['is_caregiver'] ? $activity_details['value'] ?? null : null,
            'provider_comment' => $activity_details['is_caregiver'] ? $activity_details['provider_comment'] ?? null : null,
            //'sub_activity_image' => $activity_details['is_caregiver'] ? $activity_details,
            'status' => $activity_details['is_caregiver'] ? 'completed' : 'not_completed'
        ]);
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
    // 
    protected function createDailyRepetitions($appointments, $activity_detail, $group_id, $activity_details, $activity_id)
    {
        $initial_start_time = $activity_details['start_time'];

        foreach ($appointments as $appointment_data) {
            $appointment = $appointment_data['appointment'];

            // معالجة الأوقات المكررة
            if (!empty($activity_details['repeat_times'])) {
                $this->processRepeatTimes($activity_details['repeat_times'], $appointment_data, $activity_detail, $activity_details, $appointment);
            }
            // معالجة الفاصل الزمني بالساعات
            else if (!empty($activity_details['every_x_hours'])) {
                $this->processEveryXHours($activity_details, $appointment_data, $activity_detail, $appointment, $initial_start_time);
            }
        }
    }
    protected function processRepeatTimes($repeat_times, $appointment_data, $activity_detail, $activity_details, $appointment)
    {
        foreach ($repeat_times as $time) {
            $activity_details['start_time'] = $time;

            foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
                $this->createAppointment($activity_detail, $activity_details, $appointment, $activity_appointment_id);
            }
        }
    }
    protected function processEveryXHours($activity_details, $appointment_data, $activity_detail, $appointment, $initial_start_time)
{
    $every_x_hours = $activity_details['every_x_hours'];

    for ($i = 0; $i < $activity_details['repeat_count_per_day']; $i++) {
        $activity_details['start_time'] = Carbon::parse($initial_start_time)->addHours($i * $every_x_hours)->format('H:i');

        foreach ($appointment_data['activity_appointment_ids'] as $activity_appointment_id) {
            $this->createAppointment($activity_detail, $activity_details, $appointment, $activity_appointment_id);
        }
    }
}

}
