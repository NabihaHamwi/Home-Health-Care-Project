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
        $nurse_subservices = ['العناية بالجروح والالتهابات', 'أخذ الحقن الوريدية', 'تركيب القسطرة البولية', 'العناية بمرضى السكري'];
        $physician_subservices = ['مسّاج طبي', 'العناية بالمرضى طريحي الفراش', 'علاج لتقوية العضلات الضعيفة', 'العناية بعد العمليات الجراحية'];
        $accompanying_subservices = ['العناية بالجروح والالتهابات', 'العناية بالمرضى طريحي الفراش', 'العناية بالقسطرة البولية', 'العناية بمرضى السكري', 'العناية بعد العمليات الجراحية'];

        $healthcare_provider_id = $this->faker->randomElement($providers_ids);
        $service_id = $this->faker->randomElement($services_ids);

        // generate subservice name depend on helthcare service
        $subservice_name = $service_id === 1 ? $this->faker->randomElement($nurse_subservices)
                        : ($service_id === 2 ? $this->faker->randomElement($physician_subservices)
                                             : $this->faker->randomElement($accompanying_subservices));
        return [
            'healthcare_provider_id' => $healthcare_provider_id,
            'service_id' => $service_id,
            'subservice_name' => $subservice_name,
        ];
    }
}
