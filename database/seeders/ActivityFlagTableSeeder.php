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
        ActivityFlag::create([
            'id' => 4,
            'activity_id' => 2, //درجة الحرارة
            'flag' => 1, // تمريض 
        ]);
        ActivityFlag::create([
            'id' => 5,
            'activity_id' => 2, 
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 6,
            'activity_id' => 2,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 7,
            'activity_id' => 3,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 8,
            'activity_id' => 3,
            'flag' => 3,
        ]);

        ActivityFlag::create([
            'id' => 9,
            'activity_id' => 4, // قياس السكر
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 10,
            'activity_id' => 4,
            'flag' =>2,
        ]);
        ActivityFlag::create([
            'id' => 9,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 10,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 11,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 12,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 13,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 14,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 15,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 16,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 17,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 18,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 19,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 20,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 21,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 22,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 23,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 24,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 25,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 26,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 27,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 28,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 29,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 30,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 31,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 32,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 33,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 34,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 35,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 36,
            'activity_id' => 6,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 37,
            'activity_id' => 6,
            'flag' => 3,
        ]);
    }
}
