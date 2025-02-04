<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\HealthcareProviderController as ApiHealthcareProviderController;
use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
use App\Models\HealthcareProvider;
use App\Models\Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Tymon\JWTAuth\Facades\JWTAuth;

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

    function calculate_similarity($query, $subservices, $provider)
    {
        $score = 0;
        $skills_weight = 0.4;
        $gender_weight = 0.3;
        $experience_weight = 0.15;
        $strength_weight = 0.1;
        $age_weight = 0.05;

        $strength_levels = ['basic' => 1, 'advanced' => 2, 'professional' => 3];
        $experience_levels = ['basic' => 1, 'advanced' => 2, 'professional' => 3];

        if ($query['age'])
            $score += $age_weight * (1 - abs($query['age'] - $provider->age) / 100);

        if ($query['gender'])
            $score += $gender_weight * ($query['gender'] == $provider->user->gender ? 1 : 0);

        if ($query['experience']) {
            if ($provider->experience <= 5)
                $experience = 'basic';
            else if ($provider->experience <= 10)
                $experience = 'advanced';
            else
                $experience = 'professional';
            $score += $experience_weight * (1 - abs($experience_levels[$query['experience']] - $experience_levels[$experience]) / 3);
        }

        if ($query['physical_strength'])
            $score += $strength_weight * (1 - abs($strength_levels[$query['physical_strength']] - $strength_levels[$provider->physical_strength]) / 3);

        if ($subservices) {
            $skills_1 = $subservices;
            $skills_2 = $provider->subservices->pluck('subservice_name')->toArray();
            $intersection = count(array_intersect($skills_1, $skills_2));
            $union = count(array_unique(array_merge($skills_1, $skills_2)));
            $skills_similarity = $intersection / $union;
            $score += $skills_weight * $skills_similarity;
        }

        return $score;
    }


    function search1(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $serviceId = $payload->get('service_id');
            $subservices = $payload->get('selected_subservice');
            // *** here ***
            $providers = HealthcareProviderResource::collection(Service::find($serviceId)->healthcareProviders->unique('id'));
            $results = [];

            foreach ($providers as $provider) {
                $similarity = SearchController::calculate_similarity($request, $subservices, $provider);
                $results[] = [
                    'provider' => $provider,
                    'similarity' => $similarity
                ];
            }

            usort($results, function ($a, $b) {
                return $b['similarity'] <=> $a['similarity'];
            });

            foreach ($results as $result) {
                $providersres[] = $result['provider'];
            }

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
                    'data' => $providersres
                ];
            }
            return response($response);
        } catch (\Exception $e) {
            return response()->json(['message' => 'فشلت العملية', 'error' => $e->getMessage()], 500);
        }
    }
}
