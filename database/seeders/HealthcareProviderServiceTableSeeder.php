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
        $providerServices = [
            ['healthcare_provider_id' => 1, 'service_id' => 1],
            ['healthcare_provider_id' => 2, 'service_id' => 2],
            ['healthcare_provider_id' => 3, 'service_id' => 3]
        ];

        foreach ($providerServices as $providerService) {
            HealthcareProviderService::create($providerService);
        }
    }
}
