<?php

namespace Database\Seeders;

use App\Models\ActivityFlag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityFlagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        /*
       service_id:
       1: تمريض 
       2: علاج فيزيائي
       3: مرافق صحي  
        */
        ////////////////////////////ضغط الدم/////////////////////////////////
        ActivityFlag::create([
            'id' => 1,
            'activity_id' => 1, // ضغط الدم 
            'flag' => 1, // تمريض
        ]);
        ActivityFlag::create([
            'id' => 2,
            'activity_id' => 1, // ضغط الدم
            'flag' => 2, // علاج فيزيائي
        ]);
        ActivityFlag::create([
            'id' => 3,
            'activity_id' => 1,  // ضغط الدم 
            'flag' => 3, // مرافق صحي
        ]);
        ////////////////////////////درجة الحرارة/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 4,
            'activity_id' => 2, //درجة الحرارة
            'flag' => 1, // تمريض 
        ]);
        ActivityFlag::create([
            'id' => 5,
            'activity_id' => 2,
            'flag' => 2, // علاج فيزيائي
        ]);
        ActivityFlag::create([
            'id' => 6,
            'activity_id' => 2,
            'flag' => 3, // مرافق
        ]);
        ////////////////////////////درجة الوعي/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 7,
            'activity_id' => 3, //درجة الوعي
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 8,
            'activity_id' => 3,
            'flag' => 3,
        ]);
        ////////////////////////////قياس السكر/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 9,
            'activity_id' => 4, // قياس السكر
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 10,
            'activity_id' => 4,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 11,
            'activity_id' => 4,
            'flag' => 3,
        ]);
        ////////////////////////////التمرينات الرياضية وتحريك المريض/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 12,
            'activity_id' => 5,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 13,
            'activity_id' => 5,
            'flag' => 3,
        ]);
        ////////////////////////////العناية بالفم والأسنان/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 14,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ////////////////////////////العناية بالجروح/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 15,
            'activity_id' => 7,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 16,
            'activity_id' => 7,
            'flag' => 3,
        ]);
        ////////////////////////////التغذية والسوائل/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 17,
            'activity_id' => 8,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 18,
            'activity_id' => 8,
            'flag' => 3,
        ]);
        ////////////////////////////الأدوية والجرعات/////////////////////////////////////////    
        ActivityFlag::create([
            'id' => 19,
            'activity_id' => 9,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 20,
            'activity_id' => 9,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 21,
            'activity_id' => 9,
            'flag' => 3,
        ]);
        ////////////////////////////التواصل مع الطبيب المسؤول/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 22,
            'activity_id' => 10,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 23,
            'activity_id' => 10,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 24,
            'activity_id' => 10,
            'flag' => 3,
        ]);
        ////////////////////////////الرعاية الشخصية والنظافة/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 25,
            'activity_id' => 11,
            'flag' => 3,
        ]);
        ////////////////////////////النوم والراحة/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 26,
            'activity_id' => 12,
            'flag' => 3,
        ]);
        ////////////////////////////التواصل الفعال مع المريض/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 27,
            'activity_id' => 13,
            'flag' => 3,
        ]);
        /////////////////////////////فحص الجلد/////////////////////////////////////////

        ActivityFlag::create([
            'id' => 28,
            'activity_id' => 14,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 29,
            'activity_id' => 14,
            'flag' => 3,
        ]);
        ////////////////////////////الاجراءات المتخذة للسلامة/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 30,
            'activity_id' => 15,
            'flag' => 3,
        ]);
        ////////////////////////////الأعراض التي تمت ملاحظتها/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 31,
            'activity_id' => 16,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 32,
            'activity_id' => 16,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 33,
            'activity_id' => 16,
            'flag' => 3,
        ]);
        ////////////////////////////قياس وزن المريض/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 34,
            'activity_id' => 17,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين الاسترخاء'/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 35,
            'activity_id' => 18,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 36,
            'activity_id' => 18,
            'flag' => 3,
        ]);
        ////////////////////////////التحاليل المخبرية'/////////////////////////////////////////
        ActivityFlag::create([
            'id' => 37,
            'activity_id' => 19,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 38,
            'activity_id' => 19,
            'flag' => 3,
        ]);
        ////////////////////////////تعزيز التأقلم الفعال'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 39,
            'activity_id' => 20,
            'flag' => 3,
        ]);
        ////////////////////////////معدل النبض'/////////////////////////////////////////              
        ActivityFlag::create([
            'id' => 40,
            'activity_id' => 21,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 41,
            'activity_id' => 21,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين التنسيق والتوازن'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 42,
            'activity_id' => 22,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 43,
            'activity_id' => 22,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين  السير'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 44,
            'activity_id' => 23,
            'flag' => 2,
        ]);

        ActivityFlag::create([
            'id' => 45,
            'activity_id' => 23,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين القوة والمرونة '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 46,
            'activity_id' => 24,
            'flag' => 2,
        ]);
        ////////////////////////////التدريب على التنقل'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 47,
            'activity_id' => 25,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 48,
            'activity_id' => 25,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين علاج الألم والالتهاب /////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 49,
            'activity_id' => 26,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين النطق والعلاج اللغوي '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 50,
            'activity_id' => 27,
            'flag' => 2,
        ]);

        ////////////////////////////تمارين تمدد الرقبة  '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 51,
            'activity_id' => 28,
            'flag' => 2,
        ]);

        ////////////////////////////تمارين تمدد الركبة  '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 52,
            'activity_id' => 29,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية العضلات  '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 53,
            'activity_id' => 30,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية الظهر  '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 54,
            'activity_id' => 31,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تحمل القلبي التنفسي'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 55,
            'activity_id' => 32,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية الأعصاب'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 56,
            'activity_id' => 33,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين الدعم التنفسي '/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 55,
            'activity_id' => 34,
            'flag' => 2,
        ]);
        ////////////////////////////معدل التنفس'/////////////////////////////////////////       
        ActivityFlag::create([
            'id' => 56,
            'activity_id' => 35,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 57,
            'activity_id' => 35,
            'flag' => 3,
        ]);
    }
}
