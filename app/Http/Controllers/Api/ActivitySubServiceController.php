<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ActivityResource;
use App\Models\Activity;
use App\Models\ActivitySubService;
use App\Models\HealthcareProviderSubService;
use App\Models\SubService;
use Exception;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Facades\JWTAuth;

class ActivitySubServiceController extends Controller
{
    public function Show_provider_activities(Request $request)
    {
        try {
            $token = $request->bearerToken();
            $payload = JWTAuth::setToken($token)->getPayload();
            $subservice_ids = $payload->get('selected_subservice');
            if (!$subservice_ids)
                throw new Exception('subservices is not selected, please choose subservices first');
            $provider_id = $payload->get('provider_id');
            if (!$provider_id)
                throw new Exception('care provider is not selected, please choose care provider first');
        } catch (\Exception $e) {
            $response = [
                'msg' => 'token error: could not retrieve subservices or provider_id from token',
                'status' => 500,
                'error' => $e->getMessage()
            ];
            return response($response);
        }
        try {
            $provider_subservices = HealthcareProviderSubService::where('healthcare_provider_id', $provider_id)
                ->pluck('sub_service_id')
                ->toArray();
            $available_subservices = array_intersect($subservice_ids, $provider_subservices);
            $updatedClaims = $payload->toArray();
            $updatedClaims['$available_subservices'] = $available_subservices;
            $newToken = JWTAuth::claims($updatedClaims)->fromUser(auth()->user());
            if (!$available_subservices)
                throw new Exception('care provider is not have the subservices you selected before');
            foreach ($available_subservices as $subservice) {
                $subservice = SubService::find($subservice);
                $subservice_name = $subservice->sub_service_name;
                $activities = $subservice->activities;
                $activities_data = ActivityResource::collection($activities);
                $data[$subservice_name] =  $activities_data;
            }
            $response = [
                'msg' => 'activities sended Succesfully',
                'status' => 200,
                'data' => $data,
                'token' => $newToken,
            ];
        } catch (\Exception $e) {
            $response = [
                'msg' => 'can not show activities',
                'status' => 500,
                'error' => $e->getMessage()
            ];
        }
        return response($response);
    }
}
