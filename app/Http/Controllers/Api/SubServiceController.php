<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubserviceResource;
use App\Models\HealthcareProvider;
use Illuminate\Http\Request;
use App\Models\HealthcareProviderService;
use App\Models\SubService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Facades\JWTAuth;

class SubServiceController extends Controller
{
    public function index()
    {
        // $subservices = HealthcareProviderService::all()->unique('subservice_name');
        // $subservicesCollection = SubserviceResource::collection($subservices);
        $subservices = SubserviceResource::collection(SubService::all());
        if ($subservices->isEmpty()) {
            $response = [
                'msg' => 'Subservices not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'Subservices Recived Succfully',
                'status' => 200,
                'data' => $subservices,
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
            $response = [
                'message' => 'validation errors',
                'status' => 400,
                'errors' => $validator->errors()
            ];
            return response($response);
        }

        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $updatedClaims = $payload->toArray();
            $updatedClaims['service_id'] = $service_id;
            $newToken = JWTAuth::claims($updatedClaims)->fromUser(auth()->user());
        } catch (\Exception $e) {
            return response()->json(['message' => 'Token error', 'error' => $e->getMessage()], 500);
        }

        // $subservices = HealthcareProviderService::all()->where('service_id', $service_id)->unique('subservice_name');
        // $subservicesCollection = SubserviceResource::collection($subservices);
        $subservices = SubserviceResource::collection(SubService::all()->where('service_id', $service_id));

        if ($subservices->isEmpty()) {
            $response = [
                'msg' => 'Subservices not found',
                'status' => 404,
                'data' => SubService::all(),
            ];
        } else {
            $response = [
                'msg' => 'Subservices Recived Succfully',
                'status' => 200,
                'data' => $subservices,
                'token' => $newToken,
            ];
        }
        return response($response);
    }

    public function selectSubservices(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'subservice_ids' => 'required|array',
            'subservice_ids.*' => 'integer|exists:sub_services,id'
        ]);

        if ($validator->fails()) {
            $response = [
                'message' => 'validation errors',
                'status' => 400,
                'errors' => $validator->errors()
            ];
            return response($response);
        }

        try {
            $subservices = $request->input('subservice_ids');
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $updatedClaims = $payload->toArray();
            $updatedClaims['selected_subservice'] = $subservices;
            $newToken = JWTAuth::claims($updatedClaims)->fromUser(auth()->user());
            $response = [
                'msg' => 'Subservices sended Succfully',
                'status' => 200,
                'data' => $subservices,
                'token' => $newToken,
            ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'Subservices could not send',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
    }
    public function store() {}
}
