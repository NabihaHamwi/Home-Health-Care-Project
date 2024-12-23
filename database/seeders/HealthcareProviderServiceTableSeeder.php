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
        HealthcareProviderService::factory(100)->create();
    }
}
