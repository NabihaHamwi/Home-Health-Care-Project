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
        HealthcareProvider::factory(100)->create();
        HealthcareProvider::create([
            'user_id' => 202,
            'national_number' => fake()->regexify('[0-9]{14}'),
            'age' => 31,
            'relationship_status' => fake()->randomElement(['أعزب', 'متزوج', 'أرمل', 'مطلق', '-']),
            'experience' => 5,
            'personal_image' => fake()->imageUrl(200, 200, 'people'),
            'physical_strength' => fake()->randomElement(['basic', 'advanced', 'professional']),
            'min_working_hours_per_day' => fake()->numberBetween(2, 8),
            'license_number' => fake()->unique()->bothify('??#######'), 
            'is_available' => fake()->boolean(),
            'location_name' => fake()->city,
            'latitude' => fake()->latitude(33.47, 33.55),
            'longitude' =>fake()->longitude(36.24, 36.32),
        ]);
    }
}
