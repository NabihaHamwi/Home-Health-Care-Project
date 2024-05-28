<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Models\HealthcareProvider;
use App\Models\HealthcareProviderWorktime;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

use function PHPUnit\Framework\isEmpty;

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

    public function show_my_appointments($patient_id)
    {
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $appointments = Appointment::where('patient_id', $patient_id)->get();
            return $this->successResponse(AppointmentResource::collection($appointments), 'appointment for patient retrived successfuly');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Provider not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }

    public function show_reserved_appointments($providerID, $week = 1)
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

    public function show_pending_appointments($providerID)
    {
        $appointments = Appointment::where('healthcare_provider_id', $providerID)->where('appointment_status', 'الطلب قيدالانتظار')->get();
        // التحقق من وجود مواعيد
        if ($appointments->isEmpty()) {
            return $this->errorResponse('No pending appointments found for this care provider', 404);
        }
        return $this->successResponse(AppointmentResource::collection($appointments), 'pending appointment retrieved successfully', 200);
    }

    public function show_pending_appointments_details($appointmentID, $groupID = null)
    {
        try {
            if ($groupID == null) {
                $appointments = Appointment::where('id', $appointmentID)->get();
            } else
                $appointments = Appointment::where('group_id', $groupID)->get();

            return $this->successResponse(AppointmentResource::collection($appointments), 'appointment details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse($e, 500);
        }
    }

    public function update(Request $request, $appointmentID, $groupID = null)
    {
        try {
            $status = $request->status;
            if ($groupID == null)
                $appointments = Appointment::where('id', $appointmentID)->get();
            else
                $appointments = Appointment::where('group_id', $groupID)->get();
            foreach ($appointments as $appointment) {
                if ($status) {
                    $appointment->update(['appointment_status' => 'الطلب مقبول']);
                    // $app_id = $appointment->pluck('id');
                    // @dd($app_id);
                    // $same_appointments = Appointment::where('healthcare_provider_id', )->where('appointment_date', )->get();
                    // // @dd($reversed_appointments);
                    // $valid = 0;
                    // foreach ($same_appointments as $Rappointment) {
                    //     $calc_start = Carbon::createFromFormat('H:i:s', "$Rappointment->appointment_start_time");
                    //     $start = Carbon::createFromFormat('H:i:s', "$Rappointment->appointment_start_time");
                    //     $Rduration = new Carbon($Rappointment->appointment_duration);
                    //     $Rhours = $Rduration->get('hour');
                    //     $Rminutes = $Rduration->get('minute');
                    //     $end = $calc_start->add('hour', $Rhours);
                    //     $end = $calc_start->add('minute', $Rminutes);
                    // @dd($end);
                    //     if (($start->get('hour') == $end_of_this_appointment->get('hour') && $start->get('minute') >= $end_of_this_appointment->get('minute')) || ($start->get('hour') > $end_of_this_appointment->get('hour')) || ($end->get('hour') <= $start_of_this_appointment->get('hour'))) {
                    //         $valid = 1;
                    //         break;
                    //     }
                    // }
                    // if (!$valid) {
                    //     return $this->errorResponse('the appointment time is alreday reserved', 409);
                    // }
                } else
                    $appointment->update(['appointment_status' => 'الطلب مرفوض']);
            }
            if ($appointments->isEmpty())
                return $this->errorResponse('cannot found the appointment in db to update the status', 404);
            else
                return $this->successResponse(AppointmentResource::collection($appointments), 'appointment details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse($e, 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse($e, 500);
        }
    }

    public function store(Request $request)
    {
        $providers = HealthcareProvider::pluck('id')->toArray();
        $patients = Patient::pluck('id')->toArray();
        $services = Service::pluck('id')->toArray();
        $validator = Validator::make($request->all(), [
            'appointments' => ['required', 'array'],
            'appointments.*.provider_id' => [
                'required',
                Rule::in($providers),
            ],
            'appointments.*.patient_id' => [
                'required',
                Rule::in($patients),
            ],
            'appointments.*.service_id' => [
                'required',
                Rule::in($services),
            ],
            'appointments.*.appointment_date' => [
                'required',
                'date',
                'after_or_equal:today'
            ],
            'appointments.*.appointment_start_time' => [
                'required',
                'date_format:H:i',
            ],
            'appointments.*.appointment_duration' => [
                'required',
                'date_format:H:i',
            ],
            'appointments.*.patient_location' => [
                'required',
                'string'
            ],
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            if (sizeof($request->appointments) > 1) {
                $highestGroupId = Appointment::max('group_id');
                $group_id = $highestGroupId + 1;
            } else
                $group_id = null;
            foreach ($request->appointments as $partrequest) {
                // حساب تاريخ نهاية الموعد للتأكد من القدرة على حجزه
                $start_of_appointment = new Carbon($partrequest['appointment_start_time']);
                $start_of_this_appointment = new Carbon($partrequest['appointment_start_time']);
                $duration = new Carbon($partrequest['appointment_duration']);
                $hours = $duration->get('hour');
                $minutes = $duration->get('minute');
                $end_of_this_appointment = $start_of_appointment->add('hour', $hours);
                $end_of_this_appointment = $start_of_appointment->add('minute', $minutes);
                // @dd($end_of_this_appointment);

                // استخراج اسم اليوم الموافق لتاريخ الموعد لمقارنته مع ساعات عمل مقدم الرعاية في ذلك اليوم
                $date = Carbon::parse($partrequest['appointment_date']);
                $dayName = $date->locale('ar')->isoFormat('dddd');
                $worktimes = HealthcareProviderWorktime::where('healthcare_provider_id', $partrequest['provider_id'])->where('day_name', $dayName)->get();
                $valid = 0;
                foreach ($worktimes as $worktime) {
                    $start = Carbon::createFromFormat('H:i:s', "$worktime->start_time");
                    $end = Carbon::createFromFormat('H:i:s', "$worktime->end_time");
                    if (($start->get('hour') <= $start_of_this_appointment->get('hour')) && ($end->get('hour') >= $end_of_this_appointment->get('hour'))) {
                        $valid = 1;
                        break;
                    }
                }
                // @dd($valid);
                if (!$valid) {
                    return $this->errorResponse('the appointment time is not valid', 409);
                }

                // التأكد إذا كان الموعد لا يتعارض مع موعد محجوز مسبقاً
                $reversed_appointments = Appointment::where('healthcare_provider_id', $partrequest['provider_id'])->where('appointment_date', $partrequest['appointment_date'])->get();
                // @dd($reversed_appointments);
                $valid = 0;
                foreach ($reversed_appointments as $Rappointment) {
                    $calc_start = Carbon::createFromFormat('H:i:s', "$Rappointment->appointment_start_time");
                    $start = Carbon::createFromFormat('H:i:s', "$Rappointment->appointment_start_time");
                    $Rduration = new Carbon($Rappointment->appointment_duration);
                    $Rhours = $Rduration->get('hour');
                    $Rminutes = $Rduration->get('minute');
                    $end = $calc_start->add('hour', $Rhours);
                    $end = $calc_start->add('minute', $Rminutes);
                    // @dd($end);
                    if (($start->get('hour') == $end_of_this_appointment->get('hour') && $start->get('minute') >= $end_of_this_appointment->get('minute')) || ($start->get('hour') > $end_of_this_appointment->get('hour')) || ($end->get('hour') <= $start_of_this_appointment->get('hour'))) {
                        $valid = 1;
                        break;
                    }
                }
                if (!$valid) {
                    return $this->errorResponse('the appointment time is alreday reserved', 409);
                }

                // إذا تم المرور على كل ما سبق ولم نجد أي تعرض مع الداتا بيز تتم عملية طلب الموعد
                $appointment = Appointment::create([
                    'group_id' => $group_id,
                    'patient_id' => $partrequest['patient_id'],
                    'healthcare_provider_id' => $partrequest['provider_id'],
                    'service_id' => $partrequest['service_id'],
                    'appointment_date' => $partrequest['appointment_date'],
                    'appointment_start_time' => $partrequest['appointment_start_time'],
                    'appointment_duration' => $partrequest['appointment_duration'],
                    'patient_location' => $partrequest['patient_location'],
                    'appointment_status' => 'الطلب قيدالانتظار',
                    'caregiver_status' => '-'
                ]);
            }
            return $this->successResponse($appointment, 'appointment reserved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Provider not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse($e, 500);
        }
    }
}
