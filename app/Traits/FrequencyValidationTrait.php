<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait FrequencyValidationTrait
{
    /*---------تحديد قواعد التحقق لحقل تاريخ البداية-----------------*/

    protected function startDateRules(Request $request)
    {
        return [
            'required',  // هذا السطر يحدد أن الحقل مطلوب
            'date',  // هذا السطر يحدد أن الحقل يجب أن يكون تاريخاً
            function ($attribute, $value, $fail) use ($request) {
                // التحقق مما إذا كان هناك موعد بتاريخ البداية المحدد في نفس المجموعة
                $exists = Appointment::where('group_id', $request->group_id)
                    ->whereDate('appointment_date', $value)
                    ->exists();

                // التحقق مما إذا كان تاريخ البداية المحدد قد مر
                $pastDate = Appointment::where('group_id', $request->group_id)
                    ->whereDate('appointment_date', $value)
                    ->whereDate('appointment_date', '<', Carbon::today())
                    ->exists();

                // إذا لم يكن هناك موعد بتاريخ البداية المحدد، قم بفشل التحقق
                // الرسالة: لم يتم العثور على تاريخ البداية في المواعيد.
                if (!$exists) {
                    $fail("Start date not found in appointments.");
                } else if ($pastDate) {
                    // إذا كان تاريخ البداية المحدد قد مر، قم بفشل التحقق مع رسالة توضيحية
                    // الرسالة: تاريخ البداية المحدد قد مضى.
                    $fail("The start date has already passed.");
                }
            }
        ];
    }


    /*--------لتحقق من نوع التواتر بناءً على مدخلات المستخدم------------*/

    protected function validateByFrequencyType($validator, Request $request)
    {
        //في بقلبه البيانات + القواعد
        // dd($validator);
        // تحديد اسم الدالة التي يجب استدعاؤها بناءً على نوع التواتر المدخل
        $method = 'validate' . str_replace('_', '', ucwords($request->frequencies_time, '_'));
        // dd($method);
        // التحقق من وجود الدالة المحددة
        if (!method_exists($this, $method)) {
            // إذا لم تكن الدالة موجودة، يتم إرجاع رسالة خطأ للمستخدم
            return response()->json(['error' => 'Frequency type not supported'], 400);
        }

        // استدعاء الدالة المحددة للتحقق من صحة المدخلات
        $this->{$method}($validator, $request);
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }


        return null;
    }

    /*------------------------------------ */
    protected function validateOnceTime($validator, Request $request)
    {
        //  dd($request->end_date);
        // 2. التحقق من وجود الموعد في تاريخ البداية فقط
        $this->validateDateExists(
            $validator,
            $request->start_date,
            $request->group_id,
            'start_date'
        );
        $endDate= Carbon::parse($request->start_date);
        // dd($request);
        // 1. تعيين القيم التلقائية دون اعتماد على الإدخال
        // $request->merge([
        //     'number_of_day' => 1,
        //     'end_date' => $request->start_date
        // ]);
        $request->merge([
            'end_date' =>  $endDate->toDateString(),
            'number_of_day' => 1,
        ]);
        // dd($endDate);
        // التحقق النهائي من الأخطاء
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
       // dd($request->end_date);
        // 3. تجاهل أي قيم مرسلة للحقول الأخرى (إذا أُرسلت بطريق الخطأ)
        $request->request->remove('number_of_day'); // تأكد من إزالة الحقل إذا أُرسل
        $request->request->remove('end_date'); // تأكد من إزالة الحقل إذا أُرسل
    }
    /*-----------------نشاط لمدة (x) من الموعد--------------- */
    // protected function validateNumberOfDay($validator, Request $request)
    // {
    //     // إضافة القواعد الأساسية للحقل
    //     $validator->addRules([
    //         'number_of_day' => 'required|integer|min:1'
    //     ]);

    //     // إذا فشل التحقق الأساسي، إرجاع الأخطاء
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     // حساب تواريخ البداية والنهاية بناءً على عدد التكرارات (المواعيد)
    //     $startDate = Carbon::parse($request->start_date);
    //     $appointments = Appointment::where('group_id', $request->group_id)
    //         ->whereDate('appointment_date', '>=', $startDate)
    //         ->orderBy('appointment_date')
    //         ->take($request->number_of_day)
    //         ->get(['appointment_date']);
    //     //dd($appointments);
    //     // إذا لم يكن هناك مواعيد كافية
    //     if ($appointments->count() < $request->number_of_day) {
    //         $validator->errors()->add(
    //             'number_of_day',
    //             "The maximum allowed appointments are {$appointments->count()}."
    //         );
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     // حساب تاريخ الانتهاء من آخر موعد
    //     $endDate = Carbon::parse($appointments->last()->appointment_date);
    //     $request->merge(['end_date' => $endDate->toDateString()]);
    //     // dd($endDate);
    //     // التحقق النهائي من الأخطاء
    //     if ($validator->fails()) {
    //         return response()->json(['errors' => $validator->errors()], 400);
    //     }

    //     return null;
    // }

    // // دالة مساعدة لحساب عدد المواعيد المتاحة
    // protected function getAvailableAppointmentsCount($groupId, $startDate, $endDate)
    // {
    //     return Appointment::where('group_id', $groupId)
    //         ->whereDate('appointment_date', '>=', $startDate)
    //         ->whereDate('appointment_date', '<=', $endDate)
    //         ->count();
    // }
    protected function validateNumberOfDay($validator, Request $request)
    {
        // حساب تواريخ البداية والنهاية
        $startDate = Carbon::parse($request->start_date);
        $endDate = Carbon::parse($request->end_date);
    
        // احتساب عدد المواعيد المتاحة في الفترة المحددة
        $numberOfAppointments = $this->getAvailableAppointmentsCount(
            $request->group_id,
            $startDate->toDateString(),
            $endDate->toDateString()
        );
    
        // التحقق النهائي من الأخطاء
        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    
        // دمج عدد المواعيد المحسوبة في الاستجابة
        $request->merge(['number_of_day' => $numberOfAppointments]);
    
        // إرجاع استجابة تحتوي على عدد المواعيد المتاحة
        return response()->json(['number_of_day' => $numberOfAppointments], 200);
    }
    
    // دالة مساعدة لحساب عدد المواعيد المتاحة
    protected function getAvailableAppointmentsCount($groupId, $startDate, $endDate)
    {
        return Appointment::where('group_id', $groupId)
            ->whereDate('appointment_date', '>=', $startDate)
            ->whereDate('appointment_date', '<=', $endDate)
            ->count();
    }
    

    /*---------------  التحقق لايام من الاسبوع----------------- */
    protected function validateDayOfWeek($validator, Request $request)
    {
        $validator->addRules([
            'days_of_week' => 'required|array|min:1',
            'days_of_week.*' => 'string|in:السبت,الأحد,الاثنين,الثلاثاء,الأربعاء,الخميس,الجمعة',
            'end_date' => 'nullable|date|after_or_equal:start_date'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $validator->after(function ($validator) use ($request) {
            $this->validateEndDateExists($validator, $request);
        });
    }
    /*----------حساب عدد المواعيد المتاحة ضمن مجموعة محددة---------------- */

    //     protected function getMaxAllowedDays($groupId, $startDate, $endDate)
    //     {
    //         if (Carbon::parse($startDate)->gt(Carbon::parse($endDate))) {
    //             return 0;
    //         }

    //        return  Appointment::where('group_id', $groupId)
    //             ->whereDate('appointment_date', '>=', $startDate)
    //             ->whereDate('appointment_date', '<=', $endDate)
    //             ->count();
    // // dd($count);
    //         //  return $count;
    //     }

    /*-----للتحقق مما إذا كان تاريخ الانتهاءموجودًا في المواعيد المتاحة لمجموعة-------------------- */
    protected function validateEndDateExists($validator, $request)
    {
        // dd($request->end_date);
        // التحقق من وجود end_date في المواعيد بنفس التنسيق
        $exists = Appointment::where('group_id', $request->group_id)
            ->whereDate('appointment_date', Carbon::parse($request->end_date)->toDateString())
            ->exists();
        // dd($exists);
        if (!$exists) {
            // إذا لم يكن end_date موجودًا، إضافة خطأ إلى قائمة الأخطاء
            $validator->errors()->add(
                'end_date',
                'The specified end date does not exist in the available appointments.'
            );
        }
    }

    /*------------------------------ */
    // دالة مساعدة جديدة: حساب التاريخ من عدد الأيام
    protected function calculateEndDateFromDays($request)
    {
        return Carbon::parse($request->start_date)
            ->addDays($request->number_of_day - 1)
            ->toDateString();
    }
    /*-------------------------------- */
    // دالة مساعدة جديدة: التحقق العام للتاريخ
    protected function validateDateExists($validator, $date, $groupId, $field)
    {
        // التحقق من وجود موعد في التاريخ المحدد للمجموعة
        $exists = Appointment::where('group_id', $groupId)
            ->whereDate('appointment_date', $date)
            ->exists();
        // dd($exists);
        // إذا لم يوجد موعد، إضافة خطأ إلى الحقل المحدد
        if (!$exists) {
            $validator->errors()->add(
                $field,
                'The selected period exceeds available appointments.'
            );
        }
    }
    /*-------------------------------- */
    // دالة مساعدة للحصول على آخر موعد
    protected function getLastAppointmentDate($groupId, $validator)
    {
        // جلب آخر تاريخ موعد في المجموعة المحددة
        $lastDate = Appointment::where('group_id', $groupId)
            ->orderByDesc('appointment_date')
            ->value('appointment_date');

        // إذا لم يتم العثور على مواعيد
        if (!$lastDate) {
            $validator->errors()->add(
                'group_id',
                'No appointments found in the group.'
            );
            return null;
        }

        return $lastDate;
    }
    /*----------------حساب تاريخ الانتهاء---------------- */
    protected function calculateEndDate(Request $request, $validator)
    {
        //dd($request->filled('end_date'));
        if ($request->filled('end_date')) {
            return $request->end_date;
        }
        // إذا لم يتم حسابها، استخدم آخر موعد
        return $this->getLastAppointmentDate($request->group_id, $validator);
    }
}
