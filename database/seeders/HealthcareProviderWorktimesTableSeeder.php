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
        HealthcareProviderWorktime::create([
            'id' => 1,
            'healthcare_provider_id' =>  1,
            'day_name' => 'sunday',
            'work_hours' => 4,
            'start_time' => "01:00:00",
            'end_time' => "05:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 2,
            'healthcare_provider_id' =>  1,
            'day_name' => 'sunday',
            'work_hours' => 2,
            'start_time' => "07:00:00",
            'end_time' => "09:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 3,
            'healthcare_provider_id' =>  1,
            'day_name' => 'monday',
            'work_hours' => 6,
            'start_time' => "12:00:00",
            'end_time' => "18:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 4,
            'healthcare_provider_id' =>  1,
            'day_name' => 'tuesday',
            'work_hours' => 3,
            'start_time' => "08:00:00",
            'end_time' => "11:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 5,
            'healthcare_provider_id' =>  1,
            'day_name' => 'wednesday',
            'work_hours' => 4,
            'start_time' => "10:00:00",
            'end_time' => "14:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 6,
            'healthcare_provider_id' =>  2,
            'day_name' => 'monday',
            'work_hours' => 6,
            'start_time' => "12:00:00",
            'end_time' => "18:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 7,
            'healthcare_provider_id' =>  2,
            'day_name' => 'tuesday',
            'work_hours' => 4,
            'start_time' => "11:00:00",
            'end_time' => "15:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 8,
            'healthcare_provider_id' =>  2,
            'day_name' => 'wednesday',
            'work_hours' => 2,
            'start_time' => "14:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 9,
            'healthcare_provider_id' =>  2,
            'day_name' => 'wednesday',
            'work_hours' => 2,
            'start_time' => "18:00:00",
            'end_time' => "20:00:00"
        ]);
    }
}
