<?php

namespace Database\Seeders;

use App\Models\ActivitySession;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ActivitySessionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ActivitySession::create([
            'id' => 1,
            'session_id' => 1,
            'activity_id' => 1,
            'value' => '12, 8',
            'time' => '01:15:00'
        ]);
        ActivitySession::create([
            'id' => 2,
            'session_id' => 1,
            'activity_id' => 2,
            'value' => '35 degree',
            'time' => '01:20:08'
        ]);
        ActivitySession::create([
            'id' => 3,
            'session_id' => 1,
            'activity_id' => 3,
            'value' => 'حالة جيدة',
            'time' => '01:22:24'
        ]);
        ActivitySession::create([
            'id' => 4,
            'session_id' => 2,
            'activity_id' => 1,
            'value' => '13, 9',
            'time' => '03:25:00'
        ]);
        ActivitySession::create([
            'id' => 5,
            'session_id' => 2,
            'activity_id' => 2,
            'value' => '35 degree',
            'time' => '03:00:38'
        ]);
    }
}
