<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubserviceResource;
use Illuminate\Http\Request;
use App\Models\HealthcareProviderService;

class SubServiceController extends Controller
{
    public function index()
    {
        $subservices = HealthcareProviderService::all()->unique('subservice_name');
        $subservicesCollection = SubserviceResource::collection($subservices);
        if ($subservicesCollection->isEmpty()) {
            $response = [
                'msg' => 'Subservices not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'Subservices Recived Succfully',
                'status' => 200,
                'data' => $subservicesCollection,
            ];
        }
        return response($response);
    }

    public function show($service_id)
    {
        $subservices = HealthcareProviderService::all()->where('service_id', $service_id)->unique('subservice_name');
        $subservicesCollection = SubserviceResource::collection($subservices);
        if ($subservicesCollection->isEmpty()) {
            $response = [
                'msg' => 'Subservices not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'Subservices Recived Succfully',
                'status' => 200,
                'data' => $subservicesCollection,
            ];
        }
        return response($response);
    }
}
