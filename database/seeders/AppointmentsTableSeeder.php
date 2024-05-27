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
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-28',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 2,
            'group_id' => 1,
            'patient_id' => 1,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-28',
            'appointment_start_time' => '17:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 3,
            'patient_id' => 1,
            'healthcare_provider_id' => 2,
            'service_id' => 2,
            'appointment_date' => '2024-05-31',
            'appointment_start_time' => '13:00:00',
            'appointment_duration' => '01:00:00',
            'patient_location' => 'mazzah',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 4,
            'group_id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-06-06',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
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
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-06-07',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 6,
            'group_id' => 2,
            'patient_id' => 1,
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-06-08',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 7,
            'group_id' => 3,
            'patient_id' => 2,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-29',
            'appointment_start_time' => '08:00:00',
            'appointment_duration' => '03:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 8,
            'group_id' => 3,
            'patient_id' => 2,
            'healthcare_provider_id' => 1,
            'service_id' => 1,
            'appointment_date' => '2024-05-30',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '02:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 9,
            'patient_id' => 2,
            'healthcare_provider_id' => 2,
            'service_id' => 2,
            'appointment_date' => '2024-05-31',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '03:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 10,
            'group_id' => 4,
            'patient_id' => 2,
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-05-28',
            'appointment_start_time' => '08:00:00',
            'appointment_duration' => '04:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 11,
            'group_id' => 4,
            'patient_id' => 2,
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-05-29',
            'appointment_start_time' => '08:00:00',
            'appointment_duration' => '12:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب مقبول',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
        Appointment::create([
            'id' => 12,
            'patient_id' => 3,
            'healthcare_provider_id' => 3,
            'service_id' => 3,
            'appointment_date' => '2024-06-08',
            'appointment_start_time' => '12:00:00',
            'appointment_duration' => '06:00:00',
            'patient_location' => 'midan',
            'appointment_status' => 'الطلب قيدالانتظار',
            'appointment_rating' => null,
            'caregiver_status' => '-',
            'complaint' => null
        ]);
    }
}
