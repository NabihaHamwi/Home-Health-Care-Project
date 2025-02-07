<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProviderService>
 */
class HealthcareProviderServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers_ids = DB::table('healthcare_providers')->pluck('id')->toArray();
        $services_ids =  DB::table('services')->pluck('id')->toArray();

        while (true) {
            $healthcare_provider_id = $this->faker->randomElement($providers_ids);
            $service_id = $this->faker->randomElement($services_ids);

            $exists = DB::table('healthcare_provider_service')
                ->where('healthcare_provider_id', $healthcare_provider_id)
                ->where('service_id', $service_id)
                ->exists();

            if ($exists) {
                continue;
            } else
                break;
        }

        return [
            'healthcare_provider_id' => $healthcare_provider_id,
            'service_id' => $service_id,
        ];
    }
}
