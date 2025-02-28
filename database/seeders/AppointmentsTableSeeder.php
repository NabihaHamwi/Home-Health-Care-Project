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
            'group_id' => 1,
            'patient_id' => 1,
            'healthcare_provider_id' => 101,
            'service_id' => 1,
            'day_name'=> 'Thursday',
            'appointment_date' => '2025-03-20',
            'appointment_start_time' => '10:00:00',
            'appointment_duration' => '12:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 2,
            'group_id' => 1,
            'patient_id' => 2,
            'healthcare_provider_id' => 101,
            'service_id' => 1,
            'day_name'=> 'Friday',
            'appointment_date' => '2025-03-21',
            'appointment_start_time' => '08:00:00',
            'appointment_duration' => '10:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 3,
            'patient_id' => 3,
            'healthcare_provider_id' => 101,
            'service_id' => 2,
            'day_name'=> 'Friday',
            'appointment_date' => '2025-03-21',
            'appointment_start_time' => '14:00:00',
            'appointment_duration' => '16:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 4,
            'group_id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 101,
            'service_id' => 3,
            'day_name'=> 'Friday',
            'appointment_date' => '2025-03-21',
            'appointment_start_time' => '10:00:00',
            'appointment_duration' => '12:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 5,
            'group_id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 101,
            'service_id' => 3,
            'day_name'=> 'Saturday',
            'appointment_date' => '2025-03-22',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 6,
            'group_id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 101,
            'service_id' => 3,
            'day_name'=> 'Sunday',
            'appointment_date' => '2025-03-23',
            'appointment_start_time' => '10:00:00',
            'appointment_duration' => '12:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 7,
            'group_id' => 3,
            'patient_id' => 2,
            'healthcare_provider_id' => 101,
            'service_id' => 1,
            'appointment_date' => '2025-03-26',
            'day_name'=> 'Wednesday',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        // Appointment::create([
        //     'id' => 8,
        //     'group_id' => 3,
        //     'patient_id' => 2,
        //     'healthcare_provider_id' => 1,
        //     'service_id' => 1,
        //     'appointment_date' => '2024-05-30',
        //     'appointment_start_time' => '12:00:00',
        //     'appointment_duration' => '02:00:00',
        //     'patient_location' => 'midan',
        //     'appointment_status' => 'الطلب مقبول',
        //     'appointment_rating' => null,
        //     'caregiver_status' => '-',
        //     'complaint' => null
        // ]);
        // Appointment::create([
        //     'id' => 9,
        //     'patient_id' => 2,
        //     'healthcare_provider_id' => 2,
        //     'service_id' => 2,
        //     'appointment_date' => '2024-05-31',
        //     'appointment_start_time' => '12:00:00',
        //     'appointment_duration' => '03:00:00',
        //     'patient_location' => 'midan',
        //     'appointment_status' => 'الطلب قيدالانتظار',
        //     'appointment_rating' => null,
        //     'caregiver_status' => '-',
        //     'complaint' => null
        // ]);
        // Appointment::create([
        //     'id' => 10,
        //     'group_id' => 4,
        //     'patient_id' => 2,
        //     'healthcare_provider_id' => 3,
        //     'service_id' => 3,
        //     'appointment_date' => '2024-05-28',
        //     'appointment_start_time' => '08:00:00',
        //     'appointment_duration' => '04:00:00',
        //     'patient_location' => 'midan',
        //     'appointment_status' => 'الطلب مقبول',
        //     'appointment_rating' => null,
        //     'caregiver_status' => '-',
        //     'complaint' => null
        // ]);
        // Appointment::create([
        //     'id' => 11,
        //     'group_id' => 4,
        //     'patient_id' => 2,
        //     'healthcare_provider_id' => 3,
        //     'service_id' => 3,
        //     'appointment_date' => '2024-05-29',
        //     'appointment_start_time' => '08:00:00',
        //     'appointment_duration' => '12:00:00',
        //     'patient_location' => 'midan',
        //     'appointment_status' => 'الطلب مقبول',
        //     'appointment_rating' => null,
        //     'caregiver_status' => '-',
        //     'complaint' => null
        // ]);
        // Appointment::create([
        //     'id' => 12,
        //     'patient_id' => 3,
        //     'healthcare_provider_id' => 3,
        //     'service_id' => 3,
        //     'appointment_date' => '2024-06-08',
        //     'appointment_start_time' => '12:00:00',
        //     'appointment_duration' => '06:00:00',
        //     'patient_location' => 'midan',
        //     'appointment_status' => 'الطلب قيدالانتظار',
        //     'appointment_rating' => null,
        //     'caregiver_status' => '-',
        //     'complaint' => null
        // ]);
    }
}
