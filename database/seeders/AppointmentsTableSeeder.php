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
            'service_id' => 1,
            'appointment_date' => '2024-05-19',
            'appointment_start_time' => '02:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-19',
            'appointment_start_time' => '07:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 3,
            'patient_id' => 4,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-20',
            'appointment_start_time' => '13:00:00',
            'appointment_duration' => '03:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 4,
            'patient_id' => 2,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-22',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => 'حضور',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 5,
            'patient_id' => 2,
            'healthcare_provider_id' => 2,
            'service_id' => 2,
            'appointment_date' => '2024-05-21',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 6,
            'patient_id' => 3,
            'healthcare_provider_id' => 2,
            'service_id' => 2,
            'appointment_date' => '2024-05-22',
            'appointment_start_time' => '14:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
    }
}
