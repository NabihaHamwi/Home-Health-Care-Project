<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Service::create([
            'id' => 1,
            'name' => 'تمريض منزلي',
            'description' => "رعاية طبية متكاملة في منزلك."
        ]);
        Service::create([
            'id' => 2,
            'name' => 'علاج فيزيائي',
            'description' => "العلاج الطبيعي المتخصص لتحسين حياتك اليومية."
        ]);
        Service::create([
            'id' => 3,
            'name' => 'مرافق صحّي',
            'description' => "مرافق صحي يقدم العناية والدعم اللازم." 
        ]);
        Service::create([
            'id' => 4,
            'name' => "المخبري",
            'description' => "خدمات مخبرية دقيقة وسريعة في منزلك."
        ]);
        Service::create([
            'id' => 5,
            'name' => "خدمة الطوارئ",
            'description' => "احصل على المساعدة الفورية من فريقنا المتخصص."  
        ]);
    }
}
