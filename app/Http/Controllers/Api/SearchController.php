<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\HealthcareProviderController as ApiHealthcareProviderController;
use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
use App\Models\HealthcareProvider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    public function index(SubserviceController $subserviceController, ServiceController $serviceController)
    {
        $subservicesResopnse = $subserviceController->index();
        $servicesResopnse = $serviceController->index();
        $response = ['subservices' => $subservicesResopnse, 'services' => $servicesResopnse];
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
                return $query->where('age', '=', $age);
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

    function calculate_similarity($query, $provider)
    {
        $score = 0;
        $strength_weight = 0.3;
        $specialize_weight = 0.2;
        $gender_wight = 0.2;
        $age_weight = 0.1;
        $experience_weight = 0.1;
        $skills_weight = 0.1;
        $strength_levels = ['basic' => 1, 'advanced' => 2, 'professional' => 3];
        $experience_levels = ['basic' => 1, 'advanced' => 2, 'professional' => 3];

        if ($query['age'])
            $score += $age_weight * (1 - abs($query['age'] - $provider->age) / 100);

        if ($query['experience']) {
            if ($provider->experience <= 5)
                $experience = 'basic';
            else if ($provider->experience <= 10)
                $experience = 'advanced';
            else
                $experience = 'professional';
            $score += $experience_weight * (1 - abs($strength_levels[$query['experience']] - $experience_levels[$experience]) / 3);
        }

        if ($query['physical_strength'])
            $score += $strength_weight * (1 - abs($strength_levels[$query['physical_strength']] - $strength_levels[$provider->physical_strength]) / 3);

        if (isset($query['subservices'])) {
            $skills_1 = $query['subservices'];
            $skills_2 = $provider->subservices->pluck('id')->toArray();
            $intersection = count(array_intersect($skills_1, $skills_2));
            $union = count(array_unique(array_merge($skills_1, $skills_2)));
            $skills_similarity = $intersection / $union;
            
            $score += $skills_weight * $skills_similarity;
        }

        return $score;
    }


    function search1(Request $request)
    {
        $providers = Service::find(1)->healthcareProviders;
        $results = [];

        foreach ($providers as $provider) {
            $similarity = SearchController::calculate_similarity($request, $provider);
            $results[] = ['provider' => $provider, 'similarity' => $similarity];
        }

        usort($results, function ($a, $b) {
            return $b['similarity'] <=> $a['similarity'];
        });

        if (empty($results)) {
            $response = [
                'msg' => 'providers not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'providers found',
                'status' => 200,
                'data' => ["results num: " . sizeof($results), $results]
            ];
        }
        return response($response);
    }
}
