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
    }
}
