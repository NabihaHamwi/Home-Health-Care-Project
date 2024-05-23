<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\HealthcareProviderController as ApiHealthcareProviderController;
use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
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
        $providers = HealthcareProviderResource::collection(
            HealthcareProvider::when($request->input('service'), function ($query, $service) { // الفلترة حسب الخدمات
                return $query->whereHas('services', function ($q) use ($service) {
                    $q->where('id', $service);
                });
            })->when($request->input('gender'), function ($query, $gender) { // حسب الجنس
                return $query->where('gender', $gender);
            })->when($request->age, function ($query, $age) { // حسب العمر
                return $query->where('age', '<=', $age);
            })->when($request->input('physicalStrength'), function ($query, $strength) { // حسب القوة البدنية
                return $query->where('physical_strength', $strength);
            })->when($request->experience, function ($query, $experience) { // حسب الخبرة
                return $query->where('experience', '>=', $experience);
            })->when($request->input('skill'), function ($query, $skill) { // حسب المهارات
                return $query->whereHas('skills', function ($q) use ($skill) {
                    $q->where('id', $skill);
                });
            })->get()
        );

        if ($providers->isEmpty()) {
            $response = [
                'msg' => 'providers not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'providers found',
                'status' => 200,
                'data' => $providers,
            ];
        }
        return response($response);
    }
}
