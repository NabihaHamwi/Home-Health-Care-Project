<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;
use App\Models\Appointment;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

trait FrequencyValidationTrait
{

    /*---------قواعد تاريخ البداية المشتركة------------------*/
    /**
     * تحديد قواعد التحقق لحقل تاريخ البداية
     *
     */
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

                // إذا لم يكن هناك موعد بتاريخ البداية المحدد، قم بفشل التحقق
                if (!$exists) {
                    $fail("Start date not found in appointments");
                }
            }
        ];
    }

    /*--------لتحقق من نوع التواتر بناءً على مدخلات المستخدم------------*/

    protected function validateByFrequencyType($validator, Request $request)
    {
        // تحديد اسم الدالة التي يجب استدعاؤها بناءً على نوع التواتر المدخل
        $method = 'validate' . str_replace('_', '', ucwords($request->frequencies_time, '_'));

        // التحقق من وجود الدالة المحددة
        if (!method_exists($this, $method)) {
            // إذا لم تكن الدالة موجودة، يتم إرجاع رسالة خطأ للمستخدم
            return response()->json(['error' => 'نوع التواتر غير مدعوم'], 400);
        }
        // استدعاء الدالة المحددة للتحقق من صحة المدخلات
        $this->{$method}($validator, $request);
        // يجب إرجاع null إذا لم يكن هناك أخطاء
        return null;
    }


    // التحقق لكل X يوم
    protected function validateEveryXDay($validator, Request $request)
    {
        $startDate = Carbon::parse($request->start_date);
        $endDate = $request->end_date;
        // إذا لم يتم إدخال end_date، تأكد من وجود آخر موعد
        if (!$request->end_date && !$endDate) {
            $validator->errors()->add(
                'end_date',
                'لا توجد مواعيد متاحة في المجموعة.'
            );
            return;
        }

        // احتساب عدد المواعيد بين التواريخ
        $maxDays = $this->getMaxAllowedDays(
            $request->group_id,
            $startDate->toDateString(),
            $endDate->toDateString()
        );

        // إضافة قواعد التحقق مع رسائل عربية
        $validator->addRules([
            'every_x_day' => [
                'required',
                'integer',
                'min:2',
                "max:{$maxDays}",
            ],
            'end_date' => [
                'nullable',
                'date',
                'after_or_equal:start_date',
                function ($attribute, $value, $fail) use ($request) {
                    if ($value && !Appointment::where('group_id', $request->group_id)
                        ->whereDate('appointment_date', $value)
                        ->exists()) {
                        $fail("تاريخ الانتهاء المحدد غير موجود في المواعيد.");
                    }
                }
            ]
        ]);

        // تحقق إضافي بعد القواعد
        $validator->after(function ($validator) use ($request, $maxDays) {
            if ($maxDays < 1) {
                $validator->errors()->add(
                    'start_date',
                    'لا توجد مواعيد متاحة في الفترة المحددة.'
                );
            }
        });

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }
    }

    protected function validateNumberOfDay($validator, Request $request)
    {
        $startDate = Carbon::parse($request->start_date);

        // 1. منع إدخال end_date
        if ($request->has('end_date')) {
            $validator->errors()->add('end_date', 'غير مسموح بإدخال تاريخ الانتهاء يدويًا');
            return;
        }

        // 2. احتساب عدد المواعيد من start_date إلى آخر موعد
        $maxDays = $this->getMaxAllowedDays(
            $request->group_id,
            $startDate->toDateString(),
            $this->getLastAppointmentDate($request->group_id, $validator)->toDateString()
        );

        // 3. قواعد التحقق
        $validator->addRules([
            'number_of_day' => [
                'required',
                'integer',
                'min:1',
                "max:{$maxDays}", // الحد الأقصى هو عدد المواعيد الفعلية
            ]
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        // 4. احتساب end_date بعد التحقق (للاستخدام لاحقًا)
        // $endDate = $this->calculateEndDate(
        //     $request->group_id,
        //     $startDate,
        //     $request->number_of_day
        // );
        // $request->merge(['end_date' => $endDate]); // دمج end_date



    }
    // التحقق للأيام الأسبوعية

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

    protected function getMaxAllowedDays($groupId, $startDate, $endDate = null)
    {
        $query = Appointment::where('group_id', $groupId)
            ->whereDate('appointment_date', '>=', $startDate);

        if ($endDate) {
            $query->whereDate('appointment_date', '<=', $endDate);
        }

        return $query->count(); // عدد المواعيد الفعلية
    }

    protected function validateEndDateExists($validator, $request)
    {
        // التحقق من وجود end_date في المواعيد بنفس التنسيق
        $exists = Appointment::where('group_id', $request->group_id)
            ->whereDate('appointment_date', Carbon::parse($request->end_date)->toDateString())
            ->exists();

        if (!$exists) {
            $validator->errors()->add(
                'end_date',
                'تاريخ الانتهاء المحدد غير موجود في المواعيد المتاحة.'
            );
        }
    }
    // دالة مساعدة جديدة: حساب التاريخ من عدد الأيام
    protected function calculateEndDateFromDays($request)
    {
        return Carbon::parse($request->start_date)
            ->addDays($request->number_of_day - 1)
            ->toDateString();
    }

    // دالة مساعدة جديدة: التحقق العام للتاريخ
    protected function validateDateExists($validator, $date, $groupId, $field)
    {
        $exists = Appointment::where('group_id', $groupId)
            ->whereDate('appointment_date', $date)
            ->exists();

        if (!$exists) {
            $validator->errors()->add($field, 'الفترة المحددة تتجاوز المواعيد المتاحة');
        }
    }

    // دالة مساعدة للحصول على آخر موعد
    protected function getLastAppointmentDate($groupId, $validator)
    {
        $lastDate = Appointment::where('group_id', $groupId)
            ->orderByDesc('appointment_date')
            ->value('appointment_date');
        if (!$lastDate) {
            $validator->errors()->add('group_id', 'لا توجد مواعيد في المجموعة.');
            return null;
        }
        return $lastDate;
    }

    protected function calculateEndDate(Request $request, $validator)
    {
        if ($request->filled('end_date')) {
            $this->validateDateExists(
                $validator,
                $request->end_date,
                $request->group_id,
                'end_date'
            );
            return $request->end_date;
        }
    
        if ($request->filled('number_of_day')) {
            $calculatedDate = Carbon::parse($request->start_date)
                ->addDays($request->number_of_day - 1);
    
            $this->validateDateExists(
                $validator,
                $calculatedDate->toDateString(),
                $request->group_id,
                'number_of_day'
            );
    
            return $calculatedDate->toDateString();
        }
    
        return $this->getLastAppointmentDate($request->group_id, $validator);
    }
}  
