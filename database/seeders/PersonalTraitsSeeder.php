<?php

namespace Database\Seeders;

use App\Models\PersonalTrait;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PersonalTraitsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $traits = ['صبور', 'مرن', 'غير مدخن', 'مهارة في التواصل', 'سريع البديهة', 'سريع في إنجاز العمل', 'دعم معنوي'];
        foreach ($traits as $trait) {
            PersonalTrait::create([
                'trait_name' => $trait
            ]);
        }
    }
}
