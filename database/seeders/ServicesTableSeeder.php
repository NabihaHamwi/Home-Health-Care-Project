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
            'description' => 'نقدّم أفضل خدمات التمريض في المنزل والذي يشرف عليه أمهر الكوادر التمريضية'
        ]);
        Service::create([
            'id' => 2,
            'name' => 'علاج فيزيائي',
            'description' => 'نقدّم أفضل خدمات العلاج الفيزيائي مع أمهر المعالجين الفيزيائين، ستشعر بالرضا والراحة بعد هذه الخدمة'
        ]);
        Service::create([
            'id' => 3,
            'name' => 'مرافق صحّي',
            'description' => 'مع خدمة المرافقة الصّحية تستطيع الشعور بالأمان، الراحة، بالإضافة إلى متابعة روتينك الصّحي من قبل الكوادر المتدربّة والمخصّصة'
        ]);
    }
}
