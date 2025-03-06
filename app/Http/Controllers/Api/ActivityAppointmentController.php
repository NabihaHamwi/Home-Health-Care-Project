<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;

class ActivityAppointmentController extends Controller
{
    // Retrieve appointment activities:

    public function getActivitiesAppointment1(Request $request)
    {
        try {
              //  dd($request->headers->all());
            // تسجيل جميع الـ Headers في ملف اللوغ
            // Log::info('Headers:', $request->headers->all());

            // // إعداد استجابة تجريبية لعرض الـ Headers
            // return response()->json([
            //     'headers' => $request->headers->all(),
            // ]);
            // جلب التوكن من الطلب
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'No token provided'], 400);
            }
    
            // التحقق من صحة التوكن واستخراج البيانات
            $payload = JWTAuth::setToken($token)->getPayload();
            $group_id = $payload->get('group_id');
            $appointment_id = $payload->get('appointment_id');
            
            // التحقق من وجود group_id أو appointment_id في التوكن
            if (empty($group_id) && empty($appointment_id)) {
                return response()->json(['message' => 'Neither group_id nor appointment_id found in token'], 404);
            }
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token error', 'error' => $e->getMessage()], 500);
        }
    
        // استخدام group_id إذا كان موجودًا، وإلا استخدام appointment_id
        if (!empty($group_id)) {
            $appointments = Appointment::where('group_id', $group_id)->get();
        } else {
            $appointments = Appointment::where('id', $appointment_id)->get();
        }
    
        // التحقق من وجود المواعيد
        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'No appointments found'], 404);
        }
    
        // جلب الأنشطة المرتبطة بالمواعيد وعدم تكرارها
        $activities = $appointments->flatMap(function ($appointment) {
            return $appointment->activities;
        })->unique('id');
    
        // التحقق من وجود الأنشطة
        if ($activities->isEmpty()) {
            return response()->json(['message' => 'No activities found'], 404);
        }
    
        // تصفية الأنشطة لإرجاع اسم النشاط ومعرفه فقط
        $filtered_activities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'activity_name' => $activity->activity_name
            ];
        });
    
        return response()->json($filtered_activities);
    }
    


    /********************************************/

    public function getActivitiesAppointment(Request $request)
    {
        try {
            // جلب التوكن من الطلب
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'No token provided'], 400);
            }
    
            // التحقق من صحة التوكن واستخراج البيانات
            $payload = JWTAuth::setToken($token)->getPayload();
            $group_id = $payload->get('group_id');
            $appointment_id = $payload->get('appointment_id');
    
            // التحقق من وجود group_id أو appointment_id في التوكن
            if (empty($group_id) && empty($appointment_id)) {
                return response()->json(['message' => 'Neither group_id nor appointment_id found in token'], 404);
            }
    
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token error', 'error' => $e->getMessage()], 500);
        }
    
        // إعداد مفتاح التخزين المؤقت بناءً على المدخلات
        $cacheKey = !empty($group_id) ? "appointments_group_{$group_id}" : "appointments_appointment_{$appointment_id}";
    
        // استخدام التخزين المؤقت لجلب المواعيد
        $appointments = Cache::remember($cacheKey, 3600, function() use ($group_id, $appointment_id) {
            if (!empty($group_id)) {
                return Appointment::where('group_id', $group_id)->get();
            } else {
                return Appointment::where('id', $appointment_id)->get();
            }
        });
    
        // التحقق من وجود المواعيد
        if ($appointments->isEmpty()) {
            return response()->json(['message' => 'No appointments found'], 404);
        }
    
        // إعداد مفتاح التخزين المؤقت للأنشطة بناءً على المدخلات
        $activitiesCacheKey = "activities_" . (!empty($group_id) ? "group_{$group_id}" : "appointment_{$appointment_id}");
    
        // استخدام التخزين المؤقت لجلب الأنشطة
        $activities = Cache::remember($activitiesCacheKey, 3600, function() use ($appointments) {
            return $appointments->flatMap(function ($appointment) {
                return $appointment->activities;
            })->unique('id');
        });
    
        // التحقق من وجود الأنشطة
        if ($activities->isEmpty()) {
            return response()->json(['message' => 'No activities found'], 404);
        }
    
        // تصفية الأنشطة لإرجاع اسم النشاط ومعرفه فقط
        $filtered_activities = $activities->map(function ($activity) {
            return [
                'id' => $activity->id,
                'activity_name' => $activity->activity_name
            ];
        });
    
        return response()->json($filtered_activities);
    }
    
}
