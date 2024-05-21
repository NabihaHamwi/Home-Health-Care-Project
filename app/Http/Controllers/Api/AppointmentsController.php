<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\HealthcareProviderWorktime;
use Carbon\Carbon;
use DateTime;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    use ApiResponseTrait;

    private function calculate_end_of_the_appointment(Appointment $appointment)
    {
        $start_of_this_appointment = new Carbon($appointment->appointment_start_time);
        $duration = new Carbon($appointment->appointment_duration);
        $hours = $duration->get('hour');
        $minutes = $duration->get('minute');
        // $minutes = 30;
        $end_of_this_appointment = $start_of_this_appointment->add('hour', $hours);
        $end_of_this_appointment = $start_of_this_appointment->add('minute', $minutes);
        return $end_of_this_appointment;
    }

    private function set_the_date($day_name, $date)
    {
        if ($day_name == ('monday'))
            $date = $date->copy()->next('monday');
        if ($day_name == ('tuesday'))
            $date = $date->copy()->next('tuesday');
        if ($day_name == ('wednesday'))
            $date = $date->copy()->next('wednesday');
        if ($day_name == ('thursday'))
            $date = $date->copy()->next('thursday');
        if ($day_name == ('friday'))
            $date = $date->copy()->next('friday');
        if ($day_name == ('saturday'))
            $date = $date->copy()->next('saturday');
        return $date->copy();
    }

    public function reserved_days($providerID)
    {
        /// return the date of today and the week we are in
        $today = Carbon::now()->locale('en_US');

        /// test for another date but today
        // $day = new Carbon();
        // $day->setDate(2024, 5, 4)->locale('en_US');

        $startOfWeek = $today->startOfWeek()->format('Y-m-d');
        $endOfWeek = $today->endOfWeek()->format('Y-m-d');

        /// return the reserved days in this week from the appointment table
        $reserved_appointments = Appointment::where('healthcare_provider_id', $providerID)->where('appointment_status', 'الطلب مقبول')->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->get();

        if ($reserved_appointments->isEmpty()) {
            return $this->errorResponse('No reserveed appointments for this care provider', 404);
        }

        return $this->successResponse(AppointmentResource::collection($reserved_appointments), 'reserved appointment retrieved successfully', 200);
    }

    public function show_available_days($providerID)
    {
        /// worktimes for provider who has id = $providerID
        $worktimes = HealthcareProviderWorktime::where('healthcare_provider_id', $providerID)->get();

        /// return the date of today and the week we are in
        $today = Carbon::now()->locale('en_US');
        $startOfWeek = $today->startOfWeek()->format('Y-m-d');
        $endOfWeek = $today->endOfWeek()->format('Y-m-d');

        /// return the reserved days in this week from the appointment table
        $reserved_appointments = Appointment::where('healthcare_provider_id', $providerID)->where('appointment_status', 'الطلب مقبول')->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->get();

        /// if the work time is not reserved append it to available_times
        $available_times = [];
        foreach ($worktimes as $worktime) {
            $date = $today->startOfWeek();
            $date = $this->set_the_date($worktime->day_name, $date);

            /// start & end of work time
            $workStart = Carbon::createFromFormat('H:i:s', "$worktime->start_time");
            $workEnd = Carbon::createFromFormat('H:i:s', "$worktime->end_time");
            
            $status = false; // non-reserved
            foreach ($reserved_appointments as $appointment) {
                /// start & end of appointment
                
                $app_start = Carbon::createFromFormat('H:i:s', $appointment->appointment_start_time);
                $app_end = $this->calculate_end_of_the_appointment($appointment);

                /// if appointmentday != worktimeday
                $day = Carbon::parse($appointment->appointment_date)->isDayOfWeek($worktime->day_name);
                if (!$day) {
                    continue;
                }

                if ($workStart->get('hour') <= $app_start->get('hour') && $workEnd->get('hour') >= $app_end->get('hour')) {
                    if ($workStart->get('hour') < $app_start->get('hour') && $workStart->get('minute') <= $app_start->get('minute')) {
                        $available_times[] = [
                            'date' => $date,
                            'day_name' => $worktime->day_name,
                            'start' => $workStart->format('H:i:s'),
                            'end' => $app_start->format('H:i:s')
                        ];
                        $status = true; // reserved
                    }
                    if ($workEnd->get('hour') >= $app_end->get('hour') && $workEnd->get('minute') >= $app_end->get('minute')) {
                        $available_times[] = [
                            'date' => $date,
                            'day_name' => $worktime->day_name,
                            'start' => $app_end->format('H:i:s'),
                            'end' => $workEnd->format('H:i:s')
                        ];
                        $status = true; // reserved
                    }
                    // @dd($available_times);
                } else {
                    $available_times[] =
                        [
                            'date' => $date,
                            'day_name' => $worktime->day_name,
                            'start' => $workStart->format('H:i:s'),
                            'end' => $workEnd->format('H:i:s')
                        ];
                        $status = true; // reserved
                }
            }
            if (!$status)
                $available_times[] =
                    [
                        'date' => $date,
                        'day_name' => $worktime->day_name,
                        'start' => $workStart->format('H:i:s'),
                        'end' => $workEnd->format('H:i:s')
                    ];
        }
        // if ($available_times->isEmpty()) {
        //     return $this->errorResponse('No reserveed appointments for this care provider', 404);
        // }
        return $this->successResponse($available_times, 'available times retrieved successfully', 200);
    }

    public function show_pending_appointments($providerID)
    {
        $appointments = Appointment::where('healthcare_provider_id', $providerID)->where('appointment_status', 'الطلب قيدالانتظار')->get();
        // التحقق من وجود مواعيد
        if ($appointments->isEmpty()) {
            return $this->errorResponse('No pending appointments found for this care provider', 404);
        }
        return $this->successResponse(AppointmentResource::collection($appointments), 'pending appointment retrieved successfully', 200);
    }
}

// foreach ($reservedAppointments as $appointment) {
//     $appointmentStart = Carbon::createFromFormat('H:i:s', $appointment['start']);
//     $appointmentEnd = Carbon::createFromFormat('H:i:s', $appointment['end']);

//     // التحقق من الفترة قبل الموعد المحجوز
//     if ($workStart->lt($appointmentStart)) {
//         $availableSlots[] = [
//             'start' => $workStart->format('H:i:s'),
//             'end' => $appointmentStart->format('H:i:s')
//         ];
//     }

//     // تحديث وقت بداية العمل للفترة التالية
//     $workStart = $appointmentEnd;
// }

// // التحقق من الفترة بعد آخر موعد محجوز
// if ($workStart->lt($workEnd)) {
//     $availableSlots[] = [
//         'start' => $workStart->format('H:i:s'),
//         'end' => $workEnd->format('H:i:s')
//     ];
// }

// // إرجاع الفترات المتاحة
// return $availableSlots;
