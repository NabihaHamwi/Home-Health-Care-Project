<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\HealthcareProviderSkill;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            UsersTableSeeder::class,
            HealthCareProvidersTableSeeder::class,
            ServicesTableSeeder::class,
            HealthcareProviderServiceTableSeeder::class,
            PatientsTableSeeder::class,
            AppointmentsTableSeeder::class,
            ActivitiesTableSeeder::class,
            HealthcareProviderWorktimesTableSeeder::class,
            //ActivityFlagTableSeeder::class,
            SubServiceTableSeeder::class,
            HealthcareProviderSubServiceTableSeeder::class,
            ActivitySubServiceTableSeeder::class
        ]);
    }
}
