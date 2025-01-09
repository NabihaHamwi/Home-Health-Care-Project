<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubserviceResource;
use Illuminate\Http\Request;
use App\Models\HealthcareProviderService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(
            ['service_id' => $service_id],
            ['service_id' => 'required|integer|exists:services,id']
        );
    
        if ($validator->fails()) {
            return response()->json(['message' => 'validation errors', 'errors' => $validator->errors()], 400);
        }
        try {
            $request->session()->put('selected_service_id', $service_id);
            // \Log::info('Storing in session - selected_subservice: ' . $service_id);
        } catch (\Exception $e) {
            return response()->json(['message' => 'فشلت العملية', 'error' => $e->getMessage()], 500);
        }
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
                'session_id' => $request->session()->getId()
            ];
        }
        return response($response);
    }

    public function selectSubservices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subservice_ids' => 'required|array',
            'subservice_ids.*' => 'string|exists:healthcare_provider_service,subservice_name'
        ]);
    
        if ($validator->fails()) {
            return response()->json(['message' => 'validation errors', 'errors' => $validator->errors()], 400);
        }
        try {
            $subservice = $request->input('subservice_ids');
            $request->session()->put('selected_subservice', $subservice);
            Log::info('Storing in session - selected_subservice: ' . json_encode($subservice));
            return response()->json(['message' => 'تمت العملية بنجاح', 'data' => $subservice, 'session_id' => $request->session()->getId()], $status = 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'فشلت العملية', 'error' => $e->getMessage()], 500);
        }
    }
}
