<?php

namespace Database\Seeders;

use App\Models\HealthcareProviderSubService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HealthcareProviderSubServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HealthcareProviderSubService::factory(100)->create();
    }
}
