<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
use App\Models\HealthcareProvider;
use Illuminate\Http\Request;

class HealthcareProviderController extends Controller
{
    use ApiResponseTrait;
    
    public function index()
    {
        // $healthcareproviders = HealthcareProviderResource::collection();
        $providers = HealthcareProviderResource::collection(HealthcareProvider::all());
        // @dd($providers);   
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
    
    public function show($provider_id){
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $provider = HealthcareProvider::findOrFail($provider_id);
            return $this->successResponse(new HealthcareProviderResource($provider), 'Provider details retrieved successfully');
        } 
        catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Provider not found', 404);
        } 
        catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }
}
