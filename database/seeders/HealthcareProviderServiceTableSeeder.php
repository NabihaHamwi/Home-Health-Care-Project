<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProviderServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthcareProviderService::create([
            'healthcare_provider_id' => 101,
            'service_id' => 3,
        ]);
        HealthcareProviderService::factory(100)->create();
    }
}
