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
            'activity_id' => 1, // ضغط الدم 
            'flag' => 1, // تمريض
        ]);
        ActivityFlag::create([
            'activity_id' => 1, // ضغط الدم
            'flag' => 2, // علاج فيزيائي
        ]);
        ActivityFlag::create([
            'activity_id' => 1,  // ضغط الدم 
            'flag' => 3, // مرافق صحي
        ]);
        ////////////////////////////درجة الحرارة/////////////////////////////////////////
        ActivityFlag::create([
            'activity_id' => 2, //درجة الحرارة
            'flag' => 1, // تمريض 
        ]);
        ActivityFlag::create([
            'activity_id' => 2,
            'flag' => 2, // علاج فيزيائي
        ]);
        ActivityFlag::create([

            'activity_id' => 2,
            'flag' => 3, // مرافق
        ]);
        ////////////////////////////درجة الوعي/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 3, //درجة الوعي
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 3,
            'flag' => 3,
        ]);
        ////////////////////////////قياس السكر/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 4, // قياس السكر
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 4,
            'flag' => 2,
        ]);
        ActivityFlag::create([

            'activity_id' => 4,
            'flag' => 3,
        ]);
        ////////////////////////////التمرينات الرياضية وتحريك المريض/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 5,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 5,
            'flag' => 3,
        ]);
        ////////////////////////////العناية بالفم والأسنان/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 6,
            'flag' => 3,
        ]);
        ////////////////////////////العناية بالجروح/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 7,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 7,
            'flag' => 3,
        ]);
        ////////////////////////////التغذية والسوائل/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 8,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 8,
            'flag' => 3,
        ]);
        ////////////////////////////الأدوية والجرعات/////////////////////////////////////////    
        ActivityFlag::create([

            'activity_id' => 9,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 9,
            'flag' => 2,
        ]);
        ActivityFlag::create([

            'activity_id' => 9,
            'flag' => 3,
        ]);
        ////////////////////////////التواصل مع الطبيب المسؤول/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 10,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 10,
            'flag' => 2,
        ]);
        ActivityFlag::create([

            'activity_id' => 10,
            'flag' => 3,
        ]);
        ////////////////////////////الرعاية الشخصية والنظافة/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 11,
            'flag' => 3,
        ]);
        ////////////////////////////النوم والراحة/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 12,
            'flag' => 3,
        ]);
        ////////////////////////////التواصل الفعال مع المريض/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 13,
            'flag' => 3,
        ]);
        /////////////////////////////فحص الجلد/////////////////////////////////////////

        ActivityFlag::create([

            'activity_id' => 14,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 14,
            'flag' => 3,
        ]);
        ////////////////////////////الاجراءات المتخذة للسلامة/////////////////////////////////////////
        ActivityFlag::create([
            'activity_id' => 15,
            'flag' => 3,
        ]);
        ////////////////////////////الأعراض التي تمت ملاحظتها/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 16,
            'flag' => 1,
        ]);
        ActivityFlag::create([

            'activity_id' => 16,
            'flag' => 2,
        ]);
        ActivityFlag::create([

            'activity_id' => 16,
            'flag' => 3,
        ]);
        ////////////////////////////قياس وزن المريض/////////////////////////////////////////
        ActivityFlag::create([

            'activity_id' => 17,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين الاسترخاء'/////////////////////////////////////////
        ActivityFlag::create([
            'activity_id' => 18,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'activity_id' => 18,
            'flag' => 3,
        ]);
        ////////////////////////////التحاليل المخبرية'/////////////////////////////////////////
        ActivityFlag::create([
            'activity_id' => 19,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'activity_id' => 19,
            'flag' => 3,
        ]);
        ////////////////////////////تعزيز التأقلم الفعال'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 20,
            'flag' => 3,
        ]);
        ////////////////////////////معدل النبض'/////////////////////////////////////////              
        ActivityFlag::create([
            'activity_id' => 21,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'activity_id' => 21,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين التنسيق والتوازن'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 22,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'activity_id' => 22,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين  السير'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 23,
            'flag' => 2,
        ]);

        ActivityFlag::create([
            'activity_id' => 23,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين القوة والمرونة '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 24,
            'flag' => 2,
        ]);
        ////////////////////////////التدريب على التنقل'/////////////////////////////////////////       
        ActivityFlag::create([

            'activity_id' => 25,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'activity_id' => 25,
            'flag' => 3,
        ]);
        ////////////////////////////تمارين علاج الألم والالتهاب /////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 26,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين النطق والعلاج اللغوي '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 27,
            'flag' => 2,
        ]);

        ////////////////////////////تمارين تمدد الرقبة  '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 28,
            'flag' => 2,
        ]);

        ////////////////////////////تمارين تمدد الركبة  '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 29,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية العضلات  '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 30,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية الظهر  '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 31,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تحمل القلبي التنفسي'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 32,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين تقوية الأعصاب'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 33,
            'flag' => 2,
        ]);
        ////////////////////////////تمارين الدعم التنفسي '/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 34,
            'flag' => 2,
        ]);
        ////////////////////////////معدل التنفس'/////////////////////////////////////////       
        ActivityFlag::create([
            'activity_id' => 35,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'activity_id' => 35,
            'flag' => 3,
        ]);
    }
}
