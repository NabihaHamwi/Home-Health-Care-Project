<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use Tymon\JWTAuth\Facades\JWTAuth;

class ActivityAppointmentController extends Controller
{
    // Retrieve appointment activities:
    public function getActivitiesAppointment(Request $request)
    {
       // try {
            // جلب التوكن من الطلب
           // $token = $request->bearerToken();
          //  dd($token);
            // if (!$token) {
            //     return response()->json(['message' => 'No token provided'], 400);
            // }
           // dd($token);
            //التحقق من صحة التوكن واستخراج البيانات
            // $payload = JWTAuth::setToken($token)->getPayload();
            // $group_id = $payload->get('group_id');
            $group_id = $request->group_id;
        // } catch (\Exception $e) {
        //     return response()->json(['message' => 'Token error', 'error' => $e->getMessage()], 500);
        // }

        // جلب المواعيد المرتبطة بـ group_id
        $appointments = Appointment::where('group_id', $group_id)->get();

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
        //dd($filtered_activities);
        return response()->json($filtered_activities);

        // // إنشاء الـ JWT بالأنشطة
        // $newToken = JWTAuth::customClaims(['activities' => $filtered_activities])->tokenById($payload->get('sub'));

        // // إرجاع الـ JWT
        // return response()->json(['token' => $newToken]);
    }
}
