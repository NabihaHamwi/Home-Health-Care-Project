<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ActivityDetail;
use App\Models\ActivityDetailsFrequency;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class SubActivityFrequencyController extends Controller
{
   //retrive for activities_details under appointment activitiy

   //Update on daily sub-activity status: for controll panel
   public function updaeDailySubActivityStatus(Request $request, $frequencyId)
   {
      DB::beginTransaction();

      try {
         $frequency = ActivityDetailsFrequency::findOrFail($frequencyId);
         // dd($frequency);
         $activityDetail = $frequency->activityDetail;

         //  منع التعديل إذا كانت الحالة مكتملة مسبقًا
         if ($frequency->status === 'completed') {
            return response()->json([
               'status' => 'error',
               'message' => 'لا يمكن تعديل نشاط مكتمل'
            ], 403);
         }

         $activityType = $activityDetail->sub_activity_type;

         $validationRules = [
            'provider_comment' => 'sometimes|string|max:500',
            'sub_activity_image' => 'sometimes|image|mimes:jpeg,png,jpg,gif|max:2048'
         ];

         // التحقق الشرطي للحقول
         if (in_array($activityType, ['measure', 'medicine'])) {
            // قياس أو دواء: قيمة مطلوبة، حالة تلقائية
            $validationRules['value'] = 'required|string|max:7';
            $validationRules['status'] = 'prohibited'; // لا يسمح بإرسال الحالة
         } else {
            // نشاط أو موعد طبي: حالة مطلوبة
            $validationRules['status'] = 'required|in:completed';
            $validationRules['value'] = 'prohibited'; // لا يسمح بإرسال القيمة
         }
         //  تنفيذ التحقق
         $validator = Validator::make($request->all(), $validationRules, [
            'value.prohibited' => 'Value input is not allowed for this activity type',
            'status.prohibited' => 'Status is automatically assigned for this activity type'
         ]);

         if ($validator->fails()) {
            return response()->json([
               'status' => 'error',
               'errors' => $validator->errors()
            ], 422);
         }

         //إعداد بيانات التحديث
         $updates = [
            'sub_activity_execution_time' => now()->format('H:i:s'),
            'provider_comment' => $request->input('provider_comment')
         ];

         //  التعامل مع الحالة حسب النوع
         if (in_array($activityType, ['measure', 'medicine'])) {
            $updates['status'] = 'completed'; // تعيين تلقائي
            $updates['value'] = $request->input('value');
         } else {
            $updates['status'] = $request->input('status'); // تعيين من المستخدم
         }

         // معالجة الصورة
         if ($request->hasFile('sub_activity_image')) {
            if ($frequency->sub_activity_image) {
               Storage::delete($frequency->sub_activity_image);
            }
            $path = $request->file('sub_activity_image')->store('activity_images');
            $updates['sub_activity_image'] = $path;
         }

         // تنفيذ التحديث
         $frequency->update($updates);

         DB::commit();

         return response()->json([
            'status' => 'success',
            'data' => $frequency->makeHidden(['activityDetail']) // إخفاء العلاقة
         ], 200);
      } catch (\Exception $e) {
         DB::rollBack();
         return response()->json([
            'status' => 'error',
            'message' => $e->getMessage()
         ], 500);
      }
   }
   /*************************************************/
   //retrive daily-sub-activities to today's appointment date (frequences under today's appointment)
   public function getDailySubActivities(Request $request)
   {
      try {
         // 1. التحقق من صحة المدخلات
         $request->validate([
            'date' => 'sometimes|date_format:Y-m-d',
            'group_id' => 'nullable|required_without:appointment_id|integer',
            'appointment_id' => 'nullable|required_without:group_id|integer'
         ]);

         // 2. تحديد التاريخ المطلوب
         $date = $request->filled('date')
            ? Carbon::createFromFormat('Y-m-d', $request->date)->toDateString()
            : Carbon::today()->toDateString();

         // 3. استخراج معايير البحث
         $group_id = $request->input('group_id');
         $appointment_id = $request->input('appointment_id');

         // 4. بناء الاستعلام الديناميكي
         $activities = ActivityDetailsFrequency::whereHas('activityAppointment.appointment', function ($query) use ($date, $group_id, $appointment_id) {
            $query->whereDate('appointment_date', $date);

            if ($group_id) {
               $query->where('group_id', $group_id); // فلترة حسب المجموعة
            } else {
               $query->where('id', $appointment_id); // فلترة حسب الموعد المحدد
            }
         })
            ->get()
            ->makeHidden([
               'created_at',
               'updated_at',
               'activity_appointment_id',
               'activity_detail_id'
            ]);

         // 5. التحقق من وجود نتائج
         if ($activities->isEmpty()) {
            $message = $group_id
               ? "لا توجد أنشطة مسجلة للمجموعة $group_id بتاريخ $date"
               : "لا توجد أنشطة مسجلة للموعد $appointment_id";

            return response()->json([
               'status' => 'success',
               'message' => $message
            ], 200);
         }

         // 6. إرجاع النتائج
         return response()->json([
            'status' => 'success',
            'date' => $date,
            'filter' => $group_id ? "group_id: $group_id" : "appointment_id: $appointment_id",
            'data' => $activities
         ], 200);
      } catch (\Exception $e) {
         return response()->json([
            'status' => 'error',
            'message' => 'حدث خطأ أثناء استرجاع البيانات',
            'error' => $e->getMessage()
         ], 500);
      }
   }
}
