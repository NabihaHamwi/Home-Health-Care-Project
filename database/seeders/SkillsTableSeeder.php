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
            'skill_name' => 'الصبر'
        ]);
        Skill::create([
            'id' => 2,
            'skill_name' => 'المرونة'
        ]);
    }
}
