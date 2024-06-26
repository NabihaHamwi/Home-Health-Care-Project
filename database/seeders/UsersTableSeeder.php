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
            'role' => 'admin',
            'first_name' => 'سارة',
            'last_name' => 'ملا',
            'phone_number' => '0933XXXXXX',
            'email' => 'sara@gmail.com',
            'password' => '123456'
        ]);
        //________________________________________________
        //1
        User::create([
            'role' => 'provider',
            'first_name' => 'أحمد',
            'last_name' => 'شاهين',
            'phone_number' => '0933XXXXXX',
            'email' => 'Shaheen@gmail.com',
            'password' => '1234567'
        ]);
        //2
        User::create([
            'role' => 'provider',
            'first_name' => 'نور',
            'last_name' => 'فنيش',
            'phone_number' => '0933XXXXXX',
            'email' => 'nofi@gmail.com',
            'password' => '1234567fi'
        ]);
        //3
        User::create([
            'role' => 'provider',
            'first_name' => 'محمد',
            'last_name' => 'أنس',
            'phone_number' => '0933XXXXXX',
            'email' => 'AnasMd@gmail.com',
            'password' => '1234567'
        ]);
        //4
        User::create([
            'role' => 'provider',
            'first_name' => 'يمنى',
            'last_name' => 'سعود',
            'phone_number' => '0936XXXXXX',
            'email' => 'Saud@gmail.com',
            'password' => '1234567ns'
        ]);
        //5
        User::create([
            'role' => 'provider',
            'first_name' => 'تهاني',
            'last_name' => 'الفرا',
            'phone_number' => '0933XXXXXX',
            'email' => 'alfarra@gmail.com',
            'password' => '1234567'
        ]);
        //6
        User::create([
            'role' => 'provider',
            'first_name' => 'منى',
            'last_name' => 'معجل',
            'phone_number' => '0933XXXXXX',
            'email' => 'MonaMl@gmail.com',
            'password' => '1234567'
        ]);
        //7
        User::create([
            'role' => 'provider',
            'first_name' => 'غنوة',
            'last_name' => 'بلان',
            'phone_number' => '0933XXXXXX',
            'email' => 'GBN@gmail.com',
            'password' => '1234567'
        ]);
        //8
        User::create([
            'role' => 'provider',
            'first_name' => 'ناديا',
            'last_name' => 'حلبي',
            'phone_number' => '0933XXXXXX',
            'email' => 'nadhalabi@gmail.com',
            'password' => '1234567'
        ]);
        //9
        User::create([
            'role' => 'provider',
            'first_name' => 'نبيل',
            'last_name' => 'الحلو',
            'phone_number' => '0933XXXXXX',
            'email' => 'nabeel@gmail.com',
            'password' => '1234567'
        ]);
        //__________________________________________________________________
        //1
        User::create([
            'role' => 'user',
            'first_name' => 'ريما',
            'last_name' => 'تُرماني',
            'phone_number' => '0936XXXXXX',
            'email' => 'rema@gmail.com',
            'password' => '1234567roro'
        ]);
        //2
        User::create([
            'role' => 'user',
            'first_name' => 'منى',
            'last_name' => 'دلال',
            'phone_number' => '0936XXXXXX',
            'email' => 'mona@gmail.com',
            'password' => '1234567mona'
        ]);
        //3
        User::create([
            'role' => 'user',
            'first_name' => 'فايز',
            'last_name' => 'موالدي',
            'phone_number' => '0936XXXXXX',
            'email' => 'foz@gmail.com',
            'password' => '1234567foz'
        ]);
        //4
        User::create([
            'role' => 'user',
            'first_name' => 'محمد',
            'last_name' => 'عيد',
            'phone_number' => '0936XXXXXX',
            'email' => 'mdad@gmail.com',
            'password' => '1234567md'
        ]);
        //5
        User::create([
            'role' => 'user',
            'first_name' => 'نعيمة',
            'last_name' => 'ذو الغنى',
            'phone_number' => '0936XXXXXX',
            'email' => 'nazo@gmail.com',
            'password' => '1234567naoz'
        ]);
    }
}
