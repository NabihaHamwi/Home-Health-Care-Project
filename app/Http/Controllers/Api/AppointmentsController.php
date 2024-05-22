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
        /// to set the date to the date of current week
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

    public function reserved_days($providerID, $week = 1)
    {
        /// return the date of today and the week we are in
        ///day = today
        $day = Carbon::now()->locale('en_US');

        ///go to the next dayweek
        for ($i = 2; $i <= $week; $i++)
            $day = $day->next();

        /// test for another date but today
        // $day = new Carbon();
        // $day->setDate(2024, 5, 4)->locale('en_US');

        $startOfWeek = $day->startOfWeek()->format('Y-m-d');
        $endOfWeek = $day->endOfWeek()->format('Y-m-d');

        /// return the reserved days in this week from the appointment table
        $reserved_appointments = Appointment::where('healthcare_provider_id', $providerID)->where('appointment_status', 'الطلب مقبول')->whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->get();

        if ($reserved_appointments->isEmpty()) {
            return $this->errorResponse('No reserveed appointments for this care provider', 404);
        }

        return $this->successResponse(AppointmentResource::collection($reserved_appointments), 'reserved appointment retrieved successfully', 200);
    }

    // public function show_reserved_appointment($appointmentID)
    // {
    //     try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
    //         $appointment = Appointment::findOrFail($appointmentID);
    //         return $this->successResponse(new AppointmentResource($appointment), 'appointment details retrieved successfully');
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return $this->errorResponse('Provider not found', 404);
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return $this->errorResponse('erorr query', 500);
    //     }
    // }

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

            /// for controling to add a non reserved worktime to available_appointment[]
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

                /// check if allredy available times from this work time are in the available times
                $index = 0;
                foreach ($available_times as $available_time) {
                    if ($appointment->appointment_date == $available_time['date']) {
                        $Start = Carbon::createFromFormat('H:i:s', $available_time['start']);
                        $End = Carbon::createFromFormat('H:i:s', $available_time['end']);
                        if ($Start->get('hour') <= $app_start->get('hour') && $End->get('hour') >= $app_end->get('hour')) {
                            if ($Start->get('hour') < $app_start->get('hour')) {
                                $available_times[$index] = [
                                    'date' => $date,
                                    'day_name' => $worktime->day_name,
                                    'start' => $Start->format('H:i:s'),
                                    'end' => $app_start->format('H:i:s')
                                ];
                                $status = true; // reserved
                            }
                            if ($End->get('hour') > $app_end->get('hour')) {
                                $available_times[$index] = [
                                    'date' => $date,
                                    'day_name' => $worktime->day_name,
                                    'start' => $app_end->format('H:i:s'),
                                    'end' => $End->format('H:i:s')
                                ];
                                $status = true; // reserved
                            }
                            /// the all available_time is reserved
                            // $available_times[$index]= null;
                            unset($available_times[$index]);
                            $status = true; // reserved
                            continue;
                        }
                    }
                    $index++;
                }


                /// if there is a part of worktime reserved and part not reserved add non-reserved
                if ($workStart->get('hour') <= $app_start->get('hour') && $workEnd->get('hour') >= $app_end->get('hour')) {
                    if ($workStart->get('hour') < $app_start->get('hour')) {
                        $available_times[] = [
                            'date' => $date->format('Y-m-d'),
                            'day_name' => $worktime->day_name,
                            'start' => $workStart->format('H:i:s'),
                            'end' => $app_start->format('H:i:s')
                        ];
                        $status = true; // reserved
                    }
                    if ($workEnd->get('hour') > $app_end->get('hour')) {
                        $available_times[] = [
                            'date' => $date->format('Y-m-d'),
                            'day_name' => $worktime->day_name,
                            'start' => $app_end->format('H:i:s'),
                            'end' => $workEnd->format('H:i:s')
                        ];
                        $status = true; // reserved
                    }
                    /// the all worktime is reserved
                    $status = true; // reserved
                }
            }

            /// if work time is not reserved by any appointment
            if (!$status)
                $available_times[] =
                    [
                        'date' => $date->format('Y-m-d'),
                        'day_name' => $worktime->day_name,
                        'start' => $workStart->format('H:i:s'),
                        'end' => $workEnd->format('H:i:s')
                    ];
        }
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

    public function show_appointment($appointmentID)
    {
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $appointment = Appointment::findOrFail($appointmentID);
            return $this->successResponse(new AppointmentResource($appointment), 'appointment details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Provider not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
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
