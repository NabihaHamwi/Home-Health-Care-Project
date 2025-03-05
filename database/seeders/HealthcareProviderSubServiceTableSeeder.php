<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderSubService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProviderSubServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthcareProviderSubService::create([
            'healthcare_provider_id' => 101,
            'sub_service_id' => 10,
        ]);
        HealthcareProviderSubService::factory(100)->create();
    }
}
