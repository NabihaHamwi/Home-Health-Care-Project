<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Resources\PatientResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;
use App\Models\Appointment;


class ApiPatientController extends Controller
{
    use ApiResponseTrait;
    //اختيار موعد من مواعيد المريض

    public function selectGroupOrAppointment(Request $request)
    {
        // قواعد التحقق من الصحة
        $validator = Validator::make($request->all(), [
            'group_id' => 'required_without:appointment_id|integer|exists:appointments,group_id',
            'appointment_id' => 'required_without:group_id|integer|exists:appointments,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $claims = $payload->toArray();

            $group_id = $request->input('group_id');
            $appointment_id = $request->input('appointment_id');

            $resolved_group_id = null;
            $appointment_ids = [];

            // الحالة 1: تم إرسال group_id
            if (!empty($group_id)) {
                // التأكد من وجود المجموعة (تم التحقق مسبقًا عبر exists)
                $resolved_group_id = $group_id;

                // جلب جميع المواعيد التابعة للمجموعة
                $appointment_ids = Appointment::where('group_id', $group_id)
                    ->pluck('id')
                    ->toArray();
            }
            // الحالة 2: تم إرسال appointment_id
            else {
                // جلب بيانات الموعد (تم التحقق مسبقًا عبر exists)
                $appointment = Appointment::findOrFail($appointment_id);

                $resolved_group_id = $appointment->group_id;
                $appointment_ids = [$appointment_id];
            }

            // تحديث بيانات التوكن
            $claims['group_id'] = $resolved_group_id;
            $claims['appointment_ids'] = $appointment_ids;

            // إنشاء توكن جديد
            $newToken = JWTAuth::claims($claims)->fromUser(auth()->user());

            return response()->json([
                'message' => 'تم تحديث التوكن بنجاح',
                'group_id' => $resolved_group_id,
                'appointment_ids' => $appointment_ids,
                'token' => $newToken
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'حدث خطأ أثناء المعالجة',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    /*----------------------عرض تفاصيل المريض-------------- */

    public function show(Request $request)
    {
        try {
            $token = $request->bearerToken();
            if (!$token) {
                return response()->json(['message' => 'يجب إرسال التوكن'], 401);
            }

            // فك تشفير التوكن للحصول على الـ claims
            $payload = JWTAuth::setToken($token)->getPayload();
            $patient_id = $payload->get('patient_id');

            if (!$patient_id) {
                return response()->json(['message' => 'Patient ID not found in token'], 400);
            }

            $patient = Patient::findOrFail($patient_id)->makeHidden('created_at');
            return response()->json($patient);
        } catch (\Exception $e) {
            return response()->json(['message' => 'Could not retrieve patient details', 'error' => $e->getMessage()], 500);
        }
    }
}
