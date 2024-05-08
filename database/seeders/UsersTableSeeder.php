<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة مستخدم افتراضي
        User::create([
            'id' => 1,
            'role' => 'admin',
            'first_name' => 'Sara',
            'last_name' => 'Mlla',
            'phone_number' => '0933XXXXXX',
            'email' => 'sara@gmail.com',
            'password' => '123456'
        ]);
        User::create([
            'id' => 2,
            'role' => 'provider',
            'first_name' => 'Laila',
            'last_name' => 'Danoun',
            'phone_number' => '0933XXXXXX',
            'email' => 'laila@gmail.com',
            'password' => '1234567'
        ]);
        User::create([
            'id' => 3,
            'role' => 'user',
            'first_name' => 'Nabiha',
            'last_name' => 'Hamwi',
            'phone_number' => '0933XXXXXX',
            'email' => 'nabiha@gmail.com',
            'password' => '12345678'
        ]);
    }
}
