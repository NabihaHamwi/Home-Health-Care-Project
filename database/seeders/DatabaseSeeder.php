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
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        $this->call([
            UsersTableSeeder::class,
            HealthCareProvidersTableSeeder::class,
            ServicesTableSeeder::class,
            SkillsTableSeeder::class,
            HealthcareProviderSkillTableSeeder::class,
            HealthcareProviderServiceTableSeeder::class,
            PatientsTableSeeder::class,
            AppointmentsTableSeeder::class,
            SessionsTableSeeder::class,
            ActivitiesTableSeeder::class,
            ActivitySessionTableSeeder::class,
            HealthcareProviderWorktimesTableSeeder::class,
            ActivityFlagTableSeeder::class,
        ]);
    }
}
