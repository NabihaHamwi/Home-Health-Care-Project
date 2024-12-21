<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthcareProvider>
 */
class HealthcareProviderFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    private static $userIds = [];

    public function definition()
    {
        if (empty(self::$userIds)) {
            self::$userIds = range(2, 101);
            shuffle(self::$userIds);
        }
        $userId = array_pop(self::$userIds);

        $age = $this->faker->numberBetween(20, 60);
        $experience = $this->faker->numberBetween(0, $age - 20);

        return [
            'user_id' => $userId,
            'age' => $age,
            'relationship_status' => $this->faker->randomElement(['أعزب', 'متزوج', 'أرمل', 'مطلق', '-']),
            'experience' => $experience,
            'personal_image' => $this->faker->imageUrl(200, 200, 'people'),
            'physical_strength' => $this->faker->randomElement(['basic', 'advanced', 'professional']),
            'min_working_hours_per_day' => $this->faker->numberBetween(2, 8),
        ];
    }
}