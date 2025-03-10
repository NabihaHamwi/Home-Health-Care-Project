<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // إضافة مستخدم افتراضي
        User::create([
            'role' => 'admin',
            'first_name' => 'سارة',
            'last_name' => 'ملا',
            'phone_number' => '0933XXXXXX',
            'email' => 'sara@gmail.com',
            'email_verified_at' => now(),
            'password' => '123456',
            'remember_token' => Str::random(10),
        ]);

        User::factory()->count(100)->create(['role' => 'provider']);
        User::factory()->count(100)->create(['role' => 'user']);

        User::create([
            'role' => 'provider',
            'first_name' => 'kareem',
            'last_name' => 'provider',
            'phone_number' => '09338XXXXX',
            'email' => 'kareem@gmail.com',
            'email_verified_at' => now(),
            'password' => '1234567',
            'remember_token' => Str::random(10),
        ]);

        User::create([
            'role' => 'user',
            'first_name' => 'Hasan',
            'last_name' => 'hammoda',
            'phone_number' => '09330XXXXX',
            'email' => 'hassan@gmail.com',
            'email_verified_at' => now(),
            'password' => '12345678',
            'remember_token' => Str::random(10),
        ]);
    }
}
