<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderWorktime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProviderWorktimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $days = ['الأحد', 'الاثنين', 'الثلاثاء', 'الأربعاء', 'الخميس', 'الجمعة', 'السبت'];
        // care provider 1
        $j = 0;
        for ($i = 1; $i <= 10; $i++) {
            HealthcareProviderWorktime::create([
                'id' => $i,
                'healthcare_provider_id' =>  1,
                'day_name' => $days[$j],
                'work_hours' => 6,
                'start_time' => "08:00:00",
                'end_time' => "14:00:00"
            ]);
            HealthcareProviderWorktime::create([
                'id' => ++$i,
                'healthcare_provider_id' =>  1,
                'day_name' => $days[$j],
                'work_hours' => 2,
                'start_time' => "17:00:00",
                'end_time' => "19:00:00"
            ]);
            $j++;
        }
        HealthcareProviderWorktime::create([
            'id' => 11,
            'healthcare_provider_id' =>  1,
            'day_name' => 'الجمعة',
            'work_hours' => 2,
            'start_time' => "14:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 12,
            'healthcare_provider_id' =>  1,
            'day_name' => 'السبت',
            'work_hours' => 2,
            'start_time' => "14:00:00",
            'end_time' => "16:00:00"
        ]);

        // care provider 2
        $j = 0;
        for ($i = 13; $i <= 15; $i++) {
            HealthcareProviderWorktime::create([
                'id' => $i,
                'healthcare_provider_id' =>  2,
                'day_name' => $days[$j],
                'work_hours' => 4,
                'start_time' => "08:00:00",
                'end_time' => "12:00:00"
            ]);
            $j++;
        }
        for ($i = 16; $i <= 18; $i++) {
            HealthcareProviderWorktime::create([
                'id' => $i,
                'healthcare_provider_id' =>  2,
                'day_name' => $days[$j],
                'work_hours' => 4,
                'start_time' => "12:00:00",
                'end_time' => "16:00:00"
            ]);
            $j++;
        }

        // care provider 3
        $j = 0;
        for ($i = 19; $i <= 21; $i++) {
            HealthcareProviderWorktime::create([
                'id' => $i,
                'healthcare_provider_id' =>  3,
                'day_name' => $days[$j],
                'work_hours' => 6,
                'start_time' => "08:00:00",
                'end_time' => "14:00:00"
            ]);
            $j++;
        }
        for ($i = 22; $i <= 24; $i++) {
            HealthcareProviderWorktime::create([
                'id' => $i,
                'healthcare_provider_id' =>  3,
                'day_name' => $days[$j],
                'work_hours' => 6,
                'start_time' => "12:00:00",
                'end_time' => "18:00:00"
            ]);
            $j++;
        }
        HealthcareProviderWorktime::create([
            'id' => 25,
            'healthcare_provider_id' =>  3,
            'day_name' => 'السبت',
            'work_hours' => 6,
            'start_time' => "08:00:00",
            'end_time' => "14:00:00"
        ]);
    }
}
