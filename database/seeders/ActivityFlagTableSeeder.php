<?php

namespace Database\Seeders;

use App\Models\ActivityFlag;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivityFlagTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivityFlag::create([
            'id' => 1,
            'activity_id' => 1,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 2,
            'activity_id' => 1,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 3,
            'activity_id' => 1,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 4,
            'activity_id' => 2,
            'flag' => 1,
        ]);
        ActivityFlag::create([
            'id' => 5,
            'activity_id' => 2,
            'flag' => 3,
        ]);
        ActivityFlag::create([
            'id' => 6,
            'activity_id' => 5,
            'flag' => 2,
        ]);
        ActivityFlag::create([
            'id' => 7,
            'activity_id' => 6,
            'flag' => 3,
        ]);
    }
}
