<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Patient;
use App\Models\Session;
use App\Models\Appointment;
use App\Models\Activity;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;

class ApiSessionController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            //الدالة (paginate) عادي ترجع null  ولا داعي لمعالجته لانه هاد الطبيعي
            $sessions_view = Session::with('appointment')->paginate(10);
            return $this->successResponse(SessionResource::collection($sessions_view), 'Sessions retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }



    //_________________________________________________________________


    // عرض جميع الجلسات الخاصة بالمريض
    public function patientSessions($patientId)
    {
        try {
            $appointments = Appointment::with(['sessions'])
                ->where('patient_id', $patientId)
                ->get();
            return $this->successResponse(SessionResource::collection($appointments), 'Sessions retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }


    //_______________________________________________________________________


    public function show($session_id)
    {
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $session = Session::with(['appointment', 'activities'])->findOrFail($session_id);
            return $this->successResponse(new SessionResource($session), 'Session details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }


    //_______________________________________________________________________________________


    public function create($appointmentId)
    {
        // ابحث عن الموعد
        $appointment = Appointment::with('service.activities', 'sessions')->findOrFail($appointmentId);

        // احصل على تاريخ الموعد
        $appointmentDate = $appointment->appointment_date;

        // احصل على الخدمة المرتبطة بالموعد
        $service = $appointment->service_id;

        $sessions = Session::with(['appointment.service', 'activities' => function ($query) use ($service) {
            $query->where('flag', 'shared')
                ->orWhere('flag', $service);
        }])->whereHas('appointment.service', function ($query) use ($service) {
            $query->where('id', $service);
        })->get();

        return SessionResource::collection($sessions);

        // احصل على الملاحظات الخاصة بالجلسة
        $sessionNotes = $appointment->sessions->pluck('observation');

        // // قم بتجميع البيانات في مصفوفة وارجعها
        // return [
        //     'appointment_date' => $appointmentDate,
        //     'shared_activities' => $sharedActivities,
        //     'specific_activities' => $specificActivities,
        //     'session_notes' => $sessionNotes,
        // ];
    }
    //___________________________________________________________________________

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_time' => 'required',
            'observation' => 'required',
            'appointment_id' => 'required', // تأكد من توفير appointment_id
            'activities.*.value' => 'required|max:255',
            'activities.*.time' => 'required'
        ]);

        // في حال عدم وجودها ارسال رسالة الخطأ
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 400);
        }

        try {
            // إنشاء جلسة جديدة
            $session = new Session;
            $session->appointment_id = $request->input('appointment_id');
            $session->start_time =  $request->input('start_time');
            $session->end_time = date('H:i');
            $session->observation = $request->input('observation');
            $session->save();

            // الحصول على الأنشطة من الطلب
            $activities = $request->input('activities');

            // تكرار على كل الأنشطة وحفظها في الجدول المشترك
            foreach ($activities as $activity) {
                $session->activities()->attach($activity['id'], ['value' => $activity['value'], 'time' => $activity['time']]);
            }

            $sessionResource = new SessionResource($session);

            return $this->successResponse($sessionResource, 'Session stored successfully', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Sessions not found', 404);
        }
        // catch (\Exception $e) {
        //     // التعامل مع الاستثناءات
        //     return $this->errorResponse('An error occurred while storing the session', 500);
        // }
    }



    public function edit()
    {
    }
    public function update()
    {
    }
    public function destroy()
    {
    }




    public function session_summary($patient_id)
    {
        try {
            // البحث عن اخر موعد للمريض حيث مقدم الرعاية كان حاضرا 
            $latestAppointment = Appointment::where('patient_id', $patient_id)
                ->where('caregiver_status', 'حضور')
                ->latest('id')
                ->first();

            // وجودها ضروري لانه هي يلي بترمي الاستثناء في حال المتحول رجع قيمة null
            if (!$latestAppointment) {
                return $this->errorResponse('No appointments found for the patient', 404);
            }

            // ابحث عن آخر جلسة لهذا الموعد
            $latestSession = $latestAppointment->sessions()
                ->latest('id')
                ->with(['activities'])
                ->first();
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
}
