<?php

namespace Database\Seeders;

use App\Models\Skill;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SkillsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Skill::create([
            'id' => 1,
            'skill_name' => 'الإسعافات الأوليّة'
        ]);
        Skill::create([
            'id' => 2,
            'skill_name' => 'القدرة على أخذ الحقن الوريدية'
        ]);
        Skill::create([
            'id' => 3,
            'skill_name' => 'إدخال المريض إلى الحمام (نظافة + استحمام)'
        ]);
        Skill::create([
            'id' => 4,
            'skill_name' => 'العناية باللباس'
        ]);
        Skill::create([
            'id' => 5,
            'skill_name' => 'العناية بالجروح والالتهابات'
        ]);
        Skill::create([
            'id' => 6,
            'skill_name' => 'العناية بالفم والأسنان'
        ]);
        Skill::create([
            'id' => 7,
            'skill_name' => 'العناية بالقسطرة البولية'
        ]);
        Skill::create([
            'id' => 8,
            'skill_name' => 'العناية بالنظافة الشخصيّة للمريض'
        ]);
        Skill::create([
            'id' => 9,
            'skill_name' => 'العناية بالمرضى طريحي الفراش'
        ]);
        Skill::create([
            'id' => 10,
            'skill_name' => 'إطعام المريض'
        ]);
        Skill::create([
            'id' => 11,
            'skill_name' => 'الصبر'
        ]);
        Skill::create([
            'id' => 12,
            'skill_name' => 'المرونة'
        ]);
        Skill::create([
            'id' => 13,
            'skill_name' => 'الدعم النفسي'
        ]);
        Skill::create([
            'id' => 14,
            'skill_name' => 'مهارات التواصل مع كبار السن'
        ]);
        Skill::create([
            'id' => 15,
            'skill_name' => ''
        ]);
    }
}
