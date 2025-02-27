<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityDetail;
use App\Models\ActivityDetailsFrequency;
use App\Models\Appointment;
use Carbon\Carbon;

class SubActivityFrequencyController extends Controller
{
    //every_x_day
    protected function getAppointmentsByGroupIdAndEveryXDay($appointments, $start_date, $end_date, $every_x_day)
    {
        // مصفوفة لتخزين المواعيد حسب كل X يوم
        $filtered_appointments = [];
        $startDate = Carbon::parse($start_date);

        foreach ($appointments as $appointment) {
            // كل المواعيد من بعد تاريخ البداية
            $appointment_date = Carbon::parse($appointment->appointment_date);

            // إذا كان الفرق بين تواريخ المواعيد يمثل الفاصل الزمني المطلوب
            if ($appointment_date->diffInDays($startDate) % $every_x_day == 0) {
                $filtered_appointments[] = $appointment;
            }
        }
        //dd($filtered_appointments);
        return $filtered_appointments;
    }
/*******************************************/
protected function createAppointment($activity_detail, $activity_details, $is_caregiver, $appointment,$activity_appointment_id)
{

    ActivityDetailsFrequency::create([
        'activity_detail_id' => $activity_detail->id,
        'activity_appointment_id' => $activity_appointment_id,
        'start_time' => $activity_details['start_time'],
        'sub_activity_execution_time' => $is_caregiver ? $activity_details['sub_activity_execution_time'] ?? null : null,
        'value' => $is_caregiver ? $activity_details['value'] ?? null : null,
        'provider_comment' => $is_caregiver ? $activity_details['provider_comment'] ?? null : null,
        'sub_activity_image' => $is_caregiver ? $activity_details['sub_activity_image'] ?? null : null,
        'status' => $is_caregiver ? 'completed' : 'not_completed',
    ]);
}

}
