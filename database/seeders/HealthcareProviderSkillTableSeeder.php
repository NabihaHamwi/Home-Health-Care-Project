<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderSkill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProviderSkillTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // مصفوفة تحتوي على معرّفات المزودين والمهارات التي ترغب في ربطها
        $providerSkills = [
            ['healthcare_provider_id' => 1, 'skill_id' => 1],
            ['healthcare_provider_id' => 1, 'skill_id' => 2],
            // ['healthcare_provider_id' => 2, 'skill_id' => 1],
            // أضف المزيد حسب الحاجة
        ];

        // إدراج العلاقات في جدول الكسر
        foreach ($providerSkills as $providerSkill) {
            HealthcareProviderSkill::create($providerSkill);
        }
    }
}
