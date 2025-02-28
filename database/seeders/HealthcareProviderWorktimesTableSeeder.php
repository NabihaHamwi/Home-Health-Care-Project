<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderWorktime;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class HealthcareProviderWorktimesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // care provider 101
        HealthcareProviderWorktime::create([
            'id' => 1,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Sunday',
            'work_hours' => 6,
            'start_time' => "08:00:00",
            'end_time' => "14:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 2,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Monday',
            'work_hours' => 4,
            'start_time' => "08:00:00",
            'end_time' => "12:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 3,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Tuesday',
            'work_hours' => 7,
            'start_time' => "09:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 4,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Wednesday',
            'work_hours' => 6,
            'start_time' => "12:00:00",
            'end_time' => "18:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 5,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Monday',
            'work_hours' => 2,
            'start_time' => "14:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 6,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Thursday',
            'work_hours' => 6,
            'start_time' => "10:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 7,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Friday',
            'work_hours' => 8,
            'start_time' => "08:00:00",
            'end_time' => "16:00:00"
        ]);
        HealthcareProviderWorktime::create([
            'id' => 8,
            'healthcare_provider_id' =>  101,
            'day_name' => 'Saturday',
            'work_hours' => 8,
            'start_time' => "10:00:00",
            'end_time' => "18:00:00"
        ]);

        HealthcareProviderWorktime::factory(100)->create();
    }
}
