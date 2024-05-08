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
            ['id' => 5, 'activity_name' => 'التمرينات الرّياضيّة'],     
        ];

        foreach ($activities as $activity) {
            Activity::create($activity);
        }
        
    }
}
