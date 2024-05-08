<?php

namespace Database\Seeders;

use App\Models\HealthcareProvider;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProvidersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthcareProvider::create([
            'id' => 1,
            'user_id' => 2,
            'gender' => 'أنثى',
            'age' => 30,
            'relationship_status' => 'أعزب',
            'experience' => 5,
            'personal_image' => '',
            'physical_strength' => 'advanced',
            'min_working_hours_per_day' => 8,
        ]);
        HealthcareProvider::create([
            'id' => 2,
            'user_id' => 4,
            'gender' => 'أنثى',
            'age' => 25,
            'relationship_status' => 'متزوج',
            'experience' => 3,
            'personal_image' => '',
            'physical_strength' => 'basic',
            'min_working_hours_per_day' => 6,
        ]);
        HealthcareProvider::create([
            'id' => 3,
            'user_id' => 5,
            'gender' => 'ذكر',
            'age' => 40,
            'relationship_status' => 'أرمل',
            'experience' => 7,
            'personal_image' => '',
            'physical_strength' => 'professional',
            'min_working_hours_per_day' => 8,
        ]);
    }
}
