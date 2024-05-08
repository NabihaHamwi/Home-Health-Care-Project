<?php

namespace Database\Seeders;

use App\Models\Session;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SessionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Session::create([
            'id' => 1,
            'appointment_id' => 1,
            'duration' => '02:00:00',
            'observation' => 'this is note1',
            'start_time' => '01:00:00',
            'end_time' => '03:00:00',
        ]);
        Session::create([
            'id' => 2,
            'appointment_id' => 1,
            'duration' => '02:00:00',
            'observation' => 'this is note2',
            'start_time' => '03:00:00',
            'end_time' => '05:00:00',
        ]);
    }
}
