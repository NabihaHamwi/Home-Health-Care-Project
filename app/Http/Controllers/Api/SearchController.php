<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\HealthcareProviderController as ApiHealthcareProviderController;
use App\Http\Controllers\Controller;
use App\Models\HealthcareProvider;
use Illuminate\Http\Request;

class SearchController extends Controller
{
    public function index(SkillController $skillController, ServiceController $serviceController)
    {
        $skillsResopnse = $skillController->index();
        $servicesResopnse = $serviceController->index();
        // $data1 = json_decode($skillsResopnse, true);
        // $data2 = json_decode($servicesResopnse, true);
        // $data = array_merge($data1, $data2);
        // $response =json_encode($data);
        $response = ['skills' => $skillsResopnse, 'services' => $servicesResopnse];
        return response($response);
    }

    public function search(Request $request, ApiHealthcareProviderController $providers)
    {
        $response = $providers->index($request);
        return response($response);
    }
}
