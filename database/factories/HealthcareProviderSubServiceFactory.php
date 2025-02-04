<?php

namespace Database\Factories;

use App\Models\HealthcareProvider;
use Exception;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProviderSubService>
 */
class HealthcareProviderSubServiceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $providers_ids = DB::table('healthcare_providers')->pluck('id')->toArray();
        $nurse_subservices = [1, 2, 3, 4];
        $physician_subservices = [5, 6, 7, 8];
        $accompanying_subservices = [9, 10, 11, 12, 13];
        $lab_subservices = [14, 15, 16, 17];

        while (true) {
            $healthcare_provider_id = $this->faker->randomElement($providers_ids);
            $provider_service_ids = HealthcareProvider::find($healthcare_provider_id)->services->pluck('id')->toArray();

            // إزالة خدمة الطوارئ إن وجدت لأن ليس لها خدمات جزئية محدّدة
            $filtered_provider_service_ids = array_filter($provider_service_ids, function ($value) {
                return $value !== 5;
            });

            if (empty($filtered_provider_service_ids)) {
                continue;
            }

            $service = $this->faker->randomElement($filtered_provider_service_ids);
            if ($service == 1)
                $sub_service_id = $this->faker->randomElement($nurse_subservices);
            else if ($service == 2)
                $sub_service_id = $this->faker->randomElement($physician_subservices);
            else if ($service == 3)
                $sub_service_id = $this->faker->randomElement($accompanying_subservices);
            else if ($service == 4)
                $sub_service_id = $this->faker->randomElement($lab_subservices);

            $exists = DB::table('healthcare_provider_sub_service')
                ->where('healthcare_provider_id', $healthcare_provider_id)
                ->where('sub_service_id', $sub_service_id)
                ->exists();

            if ($exists) {
                continue;
            } else
                break;
        }
        return [
            'healthcare_provider_id' => $healthcare_provider_id,
            'sub_service_id' => $sub_service_id,
        ];
    }
}
