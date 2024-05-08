<?php

namespace Database\Seeders;

use App\Models\Patient;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Patient::create([
            'id' => 1,
            'user_id' => 3,
            'full_name' => 'patient one',
            'gender' => 'أنثى',
            'birth_date' => '1999-10-09',
            'relationship_status' => 'أعزب',
            'address' => 'xx at xx street',
            'phone_number' => '09XXXXXXXX',
            'weight' => 50.8,
            'height' => 140,
            'previous_diseases_surgeries' => '',
            'chronic_diseases' => '',
            'current_medications' => '',
            'allergies' => '',
            'family_medical_history' => '',
            'smoker' => false,
            'addiction' => '',
            'exercise_frequency' => '',
            'diet_description' => '',
            'current_symptoms' => '',
            'recent_vaccinations' => '',
        ]);
    }
}
