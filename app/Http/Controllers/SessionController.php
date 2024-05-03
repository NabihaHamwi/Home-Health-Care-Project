<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use App\Http\Resources\SessionResource;
use project\resources\views\edit;
use App\Models\User;
use App\Models\Session;
use App\Models\Appointment;
use App\Models\Activity;
use Illuminate\Http\Request;

class SessionController extends Controller
{

    public function session_summary($patient_id)
    {
        try {
            $latestAppointment = Appointment::where('patient_id', $patient_id)
                ->where('caregiver_status', 'حضور')
                ->latest('id')
                ->first();


            if (!$latestAppointment) {
                return $this->errorResponse('No appointments found for the patient', 404);
            }

            // ابحث عن آخر جلسة لهذا الموعد
            dd($latestSession = $latestAppointment->sessions()
                ->latest('id')
                ->with(['activities'])
                ->first());

            if (!$latestSession) {
                return $this->errorResponse('No sessions found for the latest appointment', 404);
            }

            // إرجاع تفاصيل الجلسة والأنشطة المتعلقة بها
            return $this->successResponse(new SessionResource($latestSession), 'Latest session summary retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Exception $e) {
            // يمكنك هنا تسجيل الخطأ أو التعامل معه بشكل مختلف
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }
    public function show($session_id)
    {
       $session = Session::with(['appointment', 'activities'])->find($session_id);

        // if (!$session) {
        //     return $this->errorResponse('Session not found', 404);
        // }
        // return $this->successResponse(new SessionResource($session), 'Session details retrieved successfully', 200);
        $activitiesInfo = [];
        //    (sessions) نحاول الوصول إلى الخصائص (النشاط) في الكائن الحالي   
        foreach ($session->activities as $activity) {
            $activitiesInfo[] = [
                'name' => $activity->activity_name,
                'value' => $activity->pivot->value,
                'time' => $activity->pivot->time,
            ];
    }
    }

    // ______________________________________

    public function create($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);
        $service = $appointment->service_id;
      dd( $appointment = Appointment::with(['activities'])->findOrFail($appointmentId));

        // احصل على تاريخ الموعد
        $appointmentDate = $appointment->appointment_date;

        // ابحث عن الموعد
       dd( $appointment = Appointment::with('service.activities', 'sessions')->findOrFail($appointmentId));

        // احصل على تاريخ الموعد
        $appointmentDate = $appointment->date;

        // احصل على الخدمة المرتبطة بالموعد
        $service = $appointment->service;

        // احصل على الأنشطة المشتركة والخاصة بالخدمة المطلوبة
        $activities = $service->activities()
            ->where(function ($query) use ($service) {
                $query->where('flag', 'shared')
                    ->orWhere('flag', $service->id);
            })->get();

        // احصل على الملاحظات الخاصة بالجلسة
        $sessionNotes = $appointment->sessions->pluck('notes');

        // قم بتجميع البيانات في مصفوفة وارجعها
        return [
            'appointment_date' => $appointmentDate,
            'flag' => $activities,
            'session_notes' => $sessionNotes,
        ];
    }
//     public function store(Request $request)
//     {
//         //التحقق من ادخال البيانات 
//         $validator = Validator::make($request->all(), [
//             'observation' => 'required',
//             'value' => 'required|max:255',
//             'time' => 'required'
//         ]);

//         // في حال عدم وجودها ارسال رسالة الخطأ
//         if ($validator->fails()) {
//             return $this->errorResponse($validator->errors(), 400);
//         }

//         try {
//             // إنشاء جلسة جديدة
//             $session = new Session;
//             $session->start_time =  $request->input('start_time');
//             $session->observation = $request->input('observation');
    
//             $session->save();

//             // الحصول على الأنشطة من الطلب
//             $activities = $request->input('activities');

//             // تكرار على كل الأنشطة وحفظها في الجدول المشترك
//             foreach ($activities as $activity) {
//                 $session->activities()->attach($activity['id'], ['value' => $activity['value'], 'time' => $activity['time']]);
//             }

//             // استخدام SessionResource لتنسيق البيانات
//             $sessionResource = new SessionResource($session);

//             return $this->successResponse($sessionResource, 'Session stored successfully', 201);
//         } catch (\Exception $e) {
//             // التعامل مع الاستثناءات
//             return $this->errorResponse('An error occurred while storing the session', 500);
//         }
// }


    // عرض جميع الجلسات الخاصة بالمريض
    public function patientSessions($patientId)
    {
        $appointments = Appointment::with(['sessions'])
            ->where('patient_id', $patientId)
            ->get();

        $appointmentsDetails = [];
        
        foreach ($appointments as $appointment) {
            $sessionsDetails = [];
            foreach ($appointment->sessions as $session) {
                $sessionsDetails[] = [
                    'observation' => $session->observation,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'duration' =>$session->duration,
                ];
            }
            $appointmentsDetails[] = [
                'appointment_date' => $appointment->appointment_date,
                'sessions' => $sessionsDetails,
            ];
        }

       return $appointmentsDetails;
    }
}
