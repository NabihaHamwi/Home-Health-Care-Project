<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
use App\Models\HealthcareProvider;
use Illuminate\Http\Request;

class HealthcareProviderController extends Controller
{
    public function index(Request $request)
    {
        // $healthcareproviders = HealthcareProviderResource::collection();
        $providers = HealthcareProviderResource::collection(
            HealthcareProvider::when($request->input('service'), function ($query, $services) { // الفلترة حسب الخدمات
                return $query->whereHas('services', function ($q) use ($services) {
                    $q->whereIn('id', $services);
                });
            })->when($request->input('gender'), function ($query, $gender) { // حسب الجنس
                return $query->whereIn('gender', $gender);
            })->when($request->age, function ($query, $age) { // حسب العمر
                return $query->where('age', '<=', $age);
            })->when($request->input('physicalStrength'), function ($query, $strength) { // حسب القوة البدنية
                return $query->whereIn('physical_strength', $strength);
            })->when($request->experience, function ($query, $experience) { // حسب الخبرة
                return $query->where('experience', '>=', $experience);
            })->when($request->input('skill'), function ($query, $skills) { // حسب المهارات
                return $query->whereHas('skills', function ($q) use ($skills) {
                    $q->whereIn('id', $skills);
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
