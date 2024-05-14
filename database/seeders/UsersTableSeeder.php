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
            'first_name' => 'سارة',
            'last_name' => 'ملا',
            'phone_number' => '0933XXXXXX',
            'email' => 'sara@gmail.com',
            'password' => '123456'
        ]);
        User::create([
            'id' => 2,
            'role' => 'provider',
            'first_name' => 'أحمد',
            'last_name' => 'شاهين',
            'phone_number' => '0933XXXXXX',
            'email' => 'Shaheen@gmail.com',
            'password' => '1234567'
        ]);
        User::create([
            'id' => 3,
            'role' => 'provider',
            'first_name' => 'أنور',
            'last_name' => 'فنيش',
            'phone_number' => '0933XXXXXX',
            'email' => 'anwarfi23@gmail.com',
            'password' => '1234567fi'
        ]);
        User::create([
            'id' => 5,
            'role' => 'provider',
            'first_name' => 'أنس',
            'last_name' => 'محمد',
            'phone_number' => '0933XXXXXX',
            'email' => 'AnasMd@gmail.com',
            'password' => '1234567'
        ]);
        User::create([
            'id' => 6,
            'role' => 'provider',
            'first_name' => 'أيمن',
            'last_name' => 'سعود',
            'phone_number' => '0936XXXXXX',
            'email' => 'Aymansu@gmail.com',
            'password' => '1234567An'
        ]);
        User::create([
            'id' => 7,
            'role' => 'provider',
            'first_name' => 'تهاني',
            'last_name' => 'الفرا',
            'phone_number' => '0933XXXXXX',
            'email' => 'alfarra@gmail.com',
            'password' => '1234567'
        ]);
        User::create([
            'id' => 8,
            'role' => 'provider',
            'first_name' => 'تهاني',
            'last_name' => 'الفرا',
            'phone_number' => '0933XXXXXX',
            'email' => 'alfarra@gmail.com',
            'password' => '1234567'
        ]);
        User::create([
            'id' => 9,
            'role' => 'user',
            'first_name' => 'ريما',
            'last_name' => 'تُرماني',
            'phone_number' => '0936XXXXXX',
            'email' => 'rema@gmail.com',
            'password' => '1234567roro'
        ]);
        User::create([
            'id' => 10,
            'role' => 'user',
            'first_name' => 'منى',
            'last_name' => 'دلال',
            'phone_number' => '0936XXXXXX',
            'email' => 'mona@gmail.com',
            'password' => '1234567mona'
        ]);
        User::create([
            'id' => 11,
            'role' => 'user',
            'first_name' => 'فايز',
            'last_name' => 'موالدي',
            'phone_number' => '0936XXXXXX',
            'email' => 'foz@gmail.com',
            'password' => '1234567foz'
        ]);
    }
}
