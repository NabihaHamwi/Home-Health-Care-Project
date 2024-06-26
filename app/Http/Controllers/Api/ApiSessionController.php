<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Service;
use App\Models\Patient;
use App\Models\Session;
use App\Models\Appointment;
use App\Models\ActivityFlag;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;

class ApiSessionController extends Controller
{
    use ApiResponseTrait;



    private function calculateDuration($startTime, $endTime)
    {
        $start = new \DateTime($startTime);
        $end = new \DateTime($endTime);
        $interval = $start->diff($end);
        return $interval->format('%h:%i'); // تنسيق المدة لساعات ودقائق
    }


    //_______________________________________________________________________________

    // عرض جميع جلسات المرضى من قبل الادمن
    public function index()
    {
        try {
            // تحميل العلاقة بين الجلسات والمرضى
            $sessions_view = Session::with(['appointment', 'appointment.patient'])->paginate(10);

            return $this->successResponse(SessionResource::collection($sessions_view), 'Sessions retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }




    //_________________________________________________________________


    //  عرض الجلسات الخاصة بمريض محدد
    public function patientSessions($patientId)
    {
        try {
            // التحقق من وجود المريض
            $patient = Patient::findOrFail($patientId);

            $appointments = Appointment::with(['sessions'])
                ->where('patient_id', $patientId)
                ->get();

            // التحقق من وجود مواعيد
            if ($appointments->isEmpty()) {
                return $this->errorResponse('No appointments found for this patient', 404);
            }

            // مصفوفة لتخزين المواعيد التي لها جلسات
            $appointmentsWithSessions = [];

            // التحقق من وجود جلسات
            foreach ($appointments as $appointment) {
                if (!$appointment->sessions->isEmpty()) {
                    // إذا كانت الجلسات موجودة، أضف الموعد إلى المصفوفة
                    $appointmentsWithSessions[] = $appointment;
                }
            }

            // التحقق من وجود مواعيد تحتوي على جلسات
            if (empty($appointmentsWithSessions)) {
                return $this->errorResponse('No appointments with sessions found for this patient', 404);
            }

            return $this->successResponse(SessionResource::collection($appointmentsWithSessions), 'Sessions retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }




    //_______________________________________________________________________

    //عرض الجلسة
    public function show($session_id)
    {
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $session = Session::with(['appointment', 'activities'])->findOrFail($session_id);
            $patient_id = $session->appointment->patient_id;
            $patient_name = Patient::where('id', $patient_id)->firstOrFail()->full_name;
            // إضافة patient_name كخاصية لكائن session
            $session->patient_name = $patient_name;
          //  @dd($session);
            return $this->successResponse(new SessionResource($session), 'Session details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }


    //_______________________________________________________________________________________


    //عرض لوحةالمتابعة
    public function monitoring_panel($patient_id)
    {
        try {
            // البحث عن اخر موعد للمريض حيث مقدم الرعاية كان حاضرا 
            $latestAppointment = Appointment::where('patient_id', $patient_id)
                ->where('caregiver_status', 'حضور')
                ->latest('id')
                ->firstOrFail();
            $patient_name = Patient::where('id', $patient_id)->firstOrFail()->full_name;
            // وجودها ضروري لانه هي يلي بترمي الاستثناء في حال المتحول رجع قيمة null
            if (!$latestAppointment) {
                return $this->errorResponse('No appointments found for the patient', 404);
            }

            // ابحث عن آخر جلسة لهذا الموعد
            $latestSession = $latestAppointment->sessions()
                ->latest('id')
                ->with(['activities'])
                ->firstOrFail();
            $latestSession->patient_name = $patient_name;
            //@dd($latestSession);
            // وجودها ضروري لانه هي يلي بترمي الاستثناء في حال المتحول رجع قيمة null
            if (!$latestSession) {
                return $this->errorResponse('No sessions found for the latest appointment', 404);
            }

            // إرجاع تفاصيل الجلسة والأنشطة المتعلقة بها
            return $this->successResponse(new SessionResource($latestSession), 'Latest session summary retrieved successfully');
        } //التقاط خطأ الاستعلامات لي بترجع null 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse(' the page not found', 404);
        } catch (\Exception $e) {
            // يمكنك هنا تسجيل الخطأ أو التعامل معه بشكل مختلف
            return $this->errorResponse('An unexpected error occurred', 500);
        }
    }


    //_________________________________________________________________________________________________________
    public function create($appointment_id)
    {
        try {
            // استرجاع الموعد حسب معرف الموعد
            $appointment = Appointment::findOrFail($appointment_id);
            //من الموعد حصلت على معرف المريض
            $patient_id  = $appointment->patient_id;
            //باستخدام نموذج المريض حيث معرف المريض حصلت على اسم المريض
            $patient_name = Patient::where('id', $patient_id)->firstOrFail('full_name');
            //من الموعد حصلت على معرف الخدمة
            $serviceId = $appointment->service_id;
            $date = $appointment->appointment_date;
            // استرجاع الأعلام المرتبطة بال service_id من جدول activity_flags
            $flags = ActivityFlag::where('flag', $serviceId)->get();

            if ($flags->isEmpty()) {
                return $this->errorResponse('لا توجد أنشطة مرتبطة بهذا العلم.', 404);
            }

            // استرجاع أسماء الأنشطة المرتبطة بالأعلام
            $activitiesNames = $flags->pluck('activity.activity_name');

            if ($activitiesNames->isEmpty()) {
                return $this->errorResponse('لا توجد أسماء أنشطة متاحة.', 404);
            }

            $data = [
                'patient_name' =>  $patient_name,
                'appointment_date' => $date,
                'activities_name' => $activitiesNames

            ];

            return $this->successResponse($data, 'تم استرجاع الأنشطة بنجاح.', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('لم يتم العثور على الموعد.', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('حدث خطأ غير متوقع.', 500);
        }
    }



    //___________________________________________________________________________


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required',
            'appointment_id' => 'required',
          //  'activities.*.value' => 'required|max:255',
           // 'activities.*.time' => 'required'
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }


        try {
            $session = new Session;
            $session->duration = $request->duration;
            $session->observation = $request->observation;
            $session->appointment_id = $request->appointment_id;
            $session->start_time = $request->start_time;
            $session->end_time = date('h:i');
            $session->created_at = now();
            $session->save();



            $activities = $request->input('activities');
            foreach ($activities as $activity) {
                $session->activities()->attach($activity['id'], [
                    'session_id' => $session->id,
                    'value' => $activity['value'],
                    'time' => $activity['time'],
                    'created_at' => now(),

                ]);
            }
            $sessionResource = new SessionResource($session);

            return $this->successResponse($sessionResource, 'Session stored successfully', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        }
    }


    //_____________________________________________________________________________________________



    // public function edit($session_id)
    // {
    //     try {
    //         // التحقق من وجود جلسة
    //         $session = Session::with(['appointment', 'activities'])->findOrFail($session_id);

    //         return $this->successResponse(new SessionResource($session), 'Session details retrieved successfully');
    //     } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
    //         return $this->errorResponse('Session not found', 404);
    //     } catch (\Illuminate\Database\QueryException $e) {
    //         return $this->errorResponse('Error query', 500);
    //     }
    // }



    //______________________________________________________________________________________



    public function update(Request $request, $sessionId)
    {
        $validator = Validator::make($request->all(), [
            'observation' => 'required',
            'activities.*.value' => 'required|max:255',
            'activities.*.time' => 'required'
        ]);

        // في حال عدم وجودها ارسال رسالة الخطأ
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        try {
            // البحث عن الجلسة
            $session = Session::findOrFail($sessionId);

            // تحديث الجلسة
            $session->observation = $request->input('observation');
            $session->updated_at = now();
            $session->save();

            // الحصول على الأنشطة من الطلب
            $activities = $request->input('activities');

            // تكرار على كل الأنشطة وتحديثها في الجدول المشترك
            foreach ($activities as $activity) {
                $session->activities()->updateExistingPivot($activity['id'], ['value' => $activity['value'], 'time' => $activity['time']]);
            }

            $sessionResource = new SessionResource($session);

            return $this->successResponse($sessionResource, 'Session updated successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Session not found', 404);
        }
    }



    //_________________________________________________________________________________________




    public function destroy($sessionId)
    {
        try {
            // البحث عن الجلسة
            $session = Session::findOrFail($sessionId);

            // حذف الجلسة
            $session->delete();

            return $this->successResponse('Session deleted successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Session not found', 404);
        }
    }
}
