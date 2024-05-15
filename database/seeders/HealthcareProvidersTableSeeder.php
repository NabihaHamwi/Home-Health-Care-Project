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
    { // معالج
        HealthcareProvider::create([
            'user_id' => 2,
            'gender' => 'ذكر',
            'age' => 40,
            'relationship_status' => 'متزوج',
            'experience' => 4,
            'personal_image' => '',
            'physical_strength' => 'basic',
            'min_working_hours_per_day' => 6,
        ]);
        //معالج
        HealthcareProvider::create([
            'user_id' => 3,
            'gender' => 'ذكر',
            'age' => 37,
            'relationship_status' => 'متزوج',
            'experience' => 4,
            'personal_image' => '',
            'physical_strength' => 'advanced',
            'min_working_hours_per_day' => 6,
        ]);
        //معالج فيزيائي
        HealthcareProvider::create([
            'user_id' => 4,
            'gender' => 'ذكر',
            'age' => 45,
            'relationship_status' => 'أرمل',
            'experience' => 10,
            'personal_image' => '',
            'physical_strength' => 'professional',
            'min_working_hours_per_day' => 6,
        ]);
        //ممرض
        HealthcareProvider::create([
            'user_id' => 10,
            'gender' => 'ذكر',
            'age' => 31,
            'relationship_status' => 'أعزب',
            'experience' => 5,
            'personal_image' => '',
            'physical_strength' => 'professional',
            'min_working_hours_per_day' => 8,
        ]);
        //ممرضة
        HealthcareProvider::create([
            'user_id' => 5,
            'gender' => 'أنثى',
            'age' => 23,
            'relationship_status' => 'أعزب',
            'experience' => 2,
            'personal_image' => '',
            'physical_strength' => 'professional',
            'min_working_hours_per_day' => 8,
        ]);
        //ممرضة
        HealthcareProvider::create([
            'user_id' => 6,
            'gender' => 'أنثى',
            'age' => 50,
            'relationship_status' => 'متزوج',
            'experience' => 25,
            'personal_image' => '',
            'physical_strength' => 'basic',
            'min_working_hours_per_day' => 8,
        ]);
        //مرافق
        HealthcareProvider::create([
            'user_id' => 7,
            'gender' => 'أنثى',
            'age' => 45,
            'relationship_status' => 'مطلق',
            'experience' => 5,
            'personal_image' => '',
            'physical_strength' => 'basic',
            'min_working_hours_per_day' => 12,
        ]);
        //مرافق
        HealthcareProvider::create([
            'user_id' => 8,
            'gender' => 'أنثى',
            'age' => 25,
            'relationship_status' => 'أعزب',
            'experience' => 25,
            'personal_image' => '',
            'physical_strength' => 'advanced',
            'min_working_hours_per_day' => 12,
        ]);
        //مرافق
        HealthcareProvider::create([
            'user_id' => 9,
            'gender' => 'أنثى',
            'age' => 22,
            'relationship_status' => 'أعزب',
            'experience' => 25,
            'personal_image' => '',
            'physical_strength' => 'advanced',
            'min_working_hours_per_day' => 8,
        ]);
    }
}
