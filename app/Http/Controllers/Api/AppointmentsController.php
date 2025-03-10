<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use App\Models\ActivityAppointment;
use App\Models\ActivitySubService;
use App\Models\Appointment;
use App\Models\AppointmentSubService;
use App\Models\HealthcareProvider;
use App\Models\HealthcareProviderWorktime;
use App\Models\Patient;
use App\Models\Service;
use Carbon\Carbon;
use DateTime;
use Exception;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Tymon\JWTAuth\Facades\JWTAuth;


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

    public function show_my_appointments(Request $request)
    {
        // retrieving patient_id from token
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $patient_id = $payload->get('patient_id');
            if (!$patient_id)
                throw new Exception('patient is not selected, please choose patient first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve patient_id from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $appointments = Appointment::where('patient_id', $patient_id)->get();
            return $this->successResponse(AppointmentResource::collection($appointments), 'appointment for patient retrived successfuly');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('appointments not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }

    public function show_my_accepted_appointments(Request $request)
    {
        // retrieving patient_id from token
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $patient_id = $payload->get('patient_id');
            if (!$patient_id)
                throw new Exception('patient is not selected, please choose patient first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve patient_id from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }
        try {
            $today = now()->locale('en_US');
            $date = $today->toDateString();
            $appointments = Appointment::where('patient_id', $patient_id)->where('appointment_status', 'الطلب مقبول')->where('appointment_date', '>=', $date)->get();
            return $this->successResponse(AppointmentResource::collection($appointments), 'appointment for patient retrived successfuly');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('appointments not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }

    public function show_reserved_appointments(Request $request, $date)
    {
        // retrieving provider_id from token
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $provider_id = $payload->get('provider_id');
            if (!$provider_id)
                throw new Exception('care provider is not selected, please choose care provider first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve provider_id from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }

        try {
            /// return the reserved days in this week from the appointment table
            $reserved_appointments = Appointment::where('healthcare_provider_id', $provider_id)->where('appointment_status', 'الطلب مقبول')->where('appointment_date', $date)->get();
            if ($reserved_appointments->isEmpty()) {
                $response = [
                    'msg' => 'all appointments for this care provider in this day is available',
                    'status' => 200,
                ];
            } else
                $response = [
                    'msg' => 'reserved appointment retrieved successfully',
                    'status' => 200,
                    'data' => AppointmentResource::collection($reserved_appointments),
                    // 'token' => $newToken,
                ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'can not retrieve reserved appointments',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
    }

    public function show_pending_appointments(Request $request)
    {
        // retrieving provider_id from token
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $provider_id = $payload->get('provider_id');
            if (!$provider_id)
                throw new Exception('care provider is not selected, please choose care provider first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve provider_id from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }

        try {
            $appointments = Appointment::where('healthcare_provider_id', $provider_id)->where('appointment_status', 'الطلب قيدالانتظار')->get();
            if ($appointments->isEmpty()) {
                $response = [
                    'msg' => 'No pending appointments for this care provider',
                    'status' => 200,
                ];
            } else
                $response = [
                    'msg' => 'appointments retrieved successfully',
                    'status' => 200,
                    'data' => AppointmentResource::collection($appointments),
                    // 'token' => $newToken,
                ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'can not retrieve appointments',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
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
                    // $appointment_date = Appointment::where('id', $appointmentID)->value('appointment_date');
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
        // retrieving patient_id & provider_id from token
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $patient_id = $payload->get('patient_id');
            if (!$patient_id)
                throw new Exception('patient is not selected, please choose patient first');
            $provider_id = $payload->get('provider_id');
            if (!$provider_id)
                throw new Exception('care provider is not selected, please choose care provider first');
            $service_id = $payload->get('service_id');
            if (!$service_id)
                throw new Exception('service is not selected, please choose service first');
            $subservices = $payload->get('$available_subservices');
            if (!$subservices)
                throw new Exception('subservices is not selected, please choose subservices first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve patient_id or provider_id or service or subservices from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }
        // $provider_id = 101;
        // $patient_id =1;
        $validator = Validator::make(
            ['provider_id' => $provider_id],
            ['provider_id' => 'required|integer|exists:healthcarwproviders,id'],
            ['patient_id' => $patient_id],
            ['patient_id' => 'required|integer|exists:patients,id'],
            ['service_id' => $service_id],
            ['service_id' => 'required|integer|exists:services,id'],
            ['subservices' => $subservices],
            ['subservices' => 'required|array|exists:sub_services,id']
        );

        $validator = Validator::make($request->all(), [
            'appointments' => ['required', 'array'],
            'appointments.*.activities_id' => [
                'required',
                'array',
                'exists:activities,id',
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
            $response = [
                'message' => 'validation errors',
                'status' => 400,
                'errors' => $validator->errors()
            ];
            return response($response);
        }
        try {
            if (sizeof($request->appointments) > 1) {
                $highestGroupId = Appointment::max('group_id');
                $group_id = $highestGroupId + 1;
            } else {
                $group_id = null;
            }

            foreach ($request->appointments as $partrequest) {
                // حساب تاريخ نهاية الموعد للتأكد من القدرة على حجزه
                $start_of_appointment = new Carbon($partrequest['appointment_start_time']);
                $start_of_this_appointment = new Carbon($partrequest['appointment_start_time']);
                $duration = new Carbon($partrequest['appointment_duration']);
                $hours = $duration->hour;
                $minutes = $duration->minute;
                $end_of_this_appointment = $start_of_appointment->addHours($hours)->addMinutes($minutes);

                // استخراج اسم اليوم الموافق لتاريخ الموعد لمقارنته مع ساعات عمل مقدم الرعاية في ذلك اليوم
                $date = Carbon::parse($partrequest['appointment_date']);
                $dayName = $date->isoFormat('dddd');

                // التحقق من أن الموعد يقع ضمن أوقات العمل
                $worktimes = HealthcareProviderWorktime::where('healthcare_provider_id', $provider_id)
                    ->where('day_name', $dayName)
                    ->get();

                $valid = false;
                foreach ($worktimes as $worktime) {
                    $start = Carbon::createFromFormat('H:i:s', $worktime->start_time);
                    $end = Carbon::createFromFormat('H:i:s', $worktime->end_time);

                    if ($start->lte($start_of_this_appointment) && $end->gte($end_of_this_appointment)) {
                        $valid = true;
                        break;
                    }
                }

                if (!$valid) {
                    return $this->errorResponse('the appointment time is not valid', 409);
                }

                // التأكد إذا كان الموعد لا يتعارض مع موعد محجوز مسبقاً
                $reversed_appointments = Appointment::where('healthcare_provider_id', $provider_id)
                    ->where('appointment_date', $partrequest['appointment_date'])
                    ->get();

                $valid = true;
                foreach ($reversed_appointments as $Rappointment) {
                    $Rstart = Carbon::createFromFormat('H:i:s', $Rappointment->appointment_start_time);
                    $Rduration = new Carbon($Rappointment->appointment_duration);
                    $Rend = $Rstart->copy()->addHours($Rduration->hour)->addMinutes($Rduration->minute);

                    if (!($Rend->lte($start_of_this_appointment) || $Rstart->gte($end_of_this_appointment))) {
                        $valid = false;
                        break;
                    }
                }

                if (!$valid) {
                    return $this->errorResponse('the appointment time is already reserved', 409);
                }

                // إذا تم المرور على كل ما سبق ولم نجد أي تعرض مع الداتا بيز تتم عملية طلب الموعد
                $appointment = Appointment::create([
                    'group_id' => $group_id,
                    'patient_id' => $patient_id,
                    'healthcare_provider_id' => $provider_id,
                    'service_id' => $service_id,
                    'day_name' => $dayName,
                    'appointment_date' => $partrequest['appointment_date'],
                    'appointment_start_time' => $partrequest['appointment_start_time'],
                    'appointment_duration' => $partrequest['appointment_duration'],
                    'patient_location' => $partrequest['patient_location'],
                    'appointment_status' => 'الطلب قيدالانتظار',
                    'caregiver_status' => '-'
                ]);
                $appointment_subservices = $subservices;
                foreach ($appointment_subservices as $subservice) {
                    AppointmentSubService::create([
                        'appointment_id' => $appointment->id,
                        'sub_service_id' => $subservice
                    ]);
                }
                $activities = $partrequest['activities_id'];
                foreach ($activities as $activity) {
                    ActivityAppointment::create([
                        'appointment_id' => $appointment->id,
                        'activity_id' => $activity
                    ]);
                }
            }
            $response = [
                'msg' => 'appointments sended Succesfully',
                'status' => 200,
            ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'appointments could not stored',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
    }

    public function selectProvider(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|integer|exists:healthcare_providers,id',
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'validation errors',
                'status' => 400,
                'errors' => $validator->errors()
            ];
            return response($response);
        }

        try {
            $provider_id = $request->input('provider_id');
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $updatedClaims = $payload->toArray();
            $updatedClaims['provider_id'] = $provider_id;
            $newToken = JWTAuth::claims($updatedClaims)->fromUser(auth()->user());
            $response = [
                'msg' => 'provider sended Succfully',
                'status' => 200,
                'data' => "provider_id sended: $provider_id",
                'token' => $newToken,
            ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'provider could not send',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
    }
}
