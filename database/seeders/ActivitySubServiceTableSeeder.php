<?php

namespace Database\Seeders;

use App\Models\ActivitySubService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySubServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivitySubService::create([
           'activity_id' => 3,
           'sub_service_id' => 10, 
        ]);
        ActivitySubService::create([
           'activity_id' => 11,
           'sub_service_id' => 10, 
        ]);
        ActivitySubService::create([
           'activity_id' => 12,
           'sub_service_id' => 10, 
        ]);
    }
}
