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

    public function show(Request $request, $service_id)
    {
        $request->session()->put('selected_service_id', $service_id);

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

    public function selectSubservices(Request $request)
    {
        try {
            $subservice = $request->input('subservice_ids');
            $request->session()->put('selected_subservice', $subservice);
            return response()->json(['message' => 'تمت العملية بنجاح', 'data' => $subservice], $status = 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'فشلت العملية', 'error' => $e->getMessage()], 500);
        }
    }
}
