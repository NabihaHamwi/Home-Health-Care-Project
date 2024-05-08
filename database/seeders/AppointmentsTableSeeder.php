<?php

namespace Database\Seeders;

use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AppointmentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Appointment::create([
            'id' => 1,
            'patient_id' => 1,
            'healthcare_provider_id' => 1,
            'appointment_date' => '2024-05-02',
            'appointment_start_time' => '01:00:00',
            'appointment_duration' => '04:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => 'حضور',
            'complaint' => null
        ]);
    }
}
