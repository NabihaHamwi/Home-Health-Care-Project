<?php

namespace Database\Seeders;

use App\Models\SubService;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SubServiceTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nurse_subservices = ['العناية بالجروح والالتهابات', 'أخذ الحقن الوريدية', 'تركيب القسطرة البولية', 'العناية بمرضى السكري'];
        $physician_subservices = ['مسّاج طبي', 'العناية بالمرضى طريحي الفراش', 'علاج لتقوية العضلات الضعيفة', 'العناية بعد العمليات الجراحية'];
        $accompanying_subservices = ['العناية بالجروح والالتهابات', 'العناية بالمرضى طريحي الفراش', 'العناية بالقسطرة البولية', 'العناية بمرضى السكري', 'العناية بعد العمليات الجراحية'];
        $lab_subservices = ['تحاليل دم أساسية', 'خضاب الدم', 'تحليل فيتامينات', 'تحليل الغدة'];
        foreach ($nurse_subservices as $service) {
            SubService::create([
                'service_id' => 1,
                'sub_service_name' => $service,
                'price' => 40000
            ]);
        }
        foreach ($physician_subservices as $service) {
            SubService::create([
                'service_id' => 2,
                'sub_service_name' => $service,
                'price' => 30000
            ]);
        }
        foreach ($accompanying_subservices as $service) {
            SubService::create([
                'service_id' => 3,
                'sub_service_name' => $service,
                'price' => 10000
            ]);
        }
        foreach ($lab_subservices as $service) {
            SubService::create([
                'service_id' => 3,
                'sub_service_name' => $service,
                'price' => 70000
            ]);
        }
    }
}
