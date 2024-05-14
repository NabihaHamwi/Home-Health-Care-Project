<?php

namespace Database\Seeders;

use App\Models\Activity;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitiesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $activities = [
            ['id' => 1, 'activity_name' => 'ضغط الدم'],
            ['id' => 2, 'activity_name' => 'درجة الحرارة'],
            ['id' => 3, 'activity_name' => 'درجة الوعي'],
            ['id' => 4, 'activity_name' => 'قياس السكر'],
            ['id' => 5, 'activity_name' => 'التمرينات الرّياضيّة وتحريك المريض'],
            ['id' => 6, 'activity_name' => 'العناية بالفم والأسنان'],
            ['id' => 7, 'activity_name' => 'العناية بالجروح'],
            ['id' => 8, 'activity_name' => 'التغذية والسوائل'],
            ['id' => 9, 'activity_name' => 'الأدوية والجرعات'],
            ['id' => 10, 'activity_name' => 'التواصل مع الطبيب المسؤول'],
            ['id' => 11, 'activity_name' => 'الرعاية الشخصية والنظافة'],
            ['id' => 12, 'activity_name' => 'النوم والراحة'],  //حالة النوم لدى المريض هل كان يعاني من الارق أم لا 
            ['id' => 13, 'activity_name' => 'التواصل مع المريض'],
            ['id' => 14, 'activity_name' => 'فحص الجلد'],
            ['id' => 15, 'activity_name' => 'الإجراءات المتخذة للسلامة'],
            ['id' => 16, 'activity_name' => 'الأعراض التي تمت ملاحظتها'],
            ['id' => 17, 'activity_name' => 'قياس وزن المريض'],
            ['id' => 18, 'activity_name' => 'قياس  المريض'],
            ['id' => 19, 'activity_name' => 'التحاليل المخبرية'],
            ['id' => 20, 'activity_name' => 'تعزيز التأقلم الفعال'], //التأقلم على المرض
            ['id' => 21, 'activity_name' => 'معدل النبض'],
            ['id' => 22, 'activity_name' => 'تمارين التنسيق والتوازن'],
            ['id' => 23, 'activity_name' => 'تمارين السير (المشي)'],
            ['id' => 24, 'activity_name' => 'تمارين التكيف العامة'],
            ['id' => 25, 'activity_name' => 'التدريب على التنقل'],
            ['id' => 26, 'activity_name' => 'تمارين علاج الألم والالتهاب'],
            ['id' => 27, 'activity_name' => 'تمارين النطاق الحركي'],
            ['id' => 28, 'activity_name' => 'تمارين النطق والعلاج اللغوي'],
            ['id' => 29, 'activity_name' => 'تمارين تمددالرقبة'],
            ['id' => 30, 'activity_name' => 'تمارين تمددالركبة'],
            ['id' => 31, 'activity_name' => 'تمارين تقوية العضلات'],
            ['id' => 32, 'activity_name' => 'تمارين تقوية الظهر'],
            ['id' => 33, 'activity_name' => 'تمارين التنفس'],
            ['id' => 34, 'activity_name' => 'تمارين تقويةالأعصاب'],
            ['id' => 35, 'activity_name' => 'تمارين تحسين الثبات'],//للعلاج الفيزيائي
            ['id' => 36, 'activity_name' => 'طريقة العلاج'],
            ['id' => 37, 'activity_name' => 'تمارين الدعم النفسي'],

        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
    }
}
