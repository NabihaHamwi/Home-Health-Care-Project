<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Activity;
use App\Models\ActivitySubService;

class ActivityController extends Controller
{

    // get all Activities under spesific subService
    public function getActivities($sub_service_id)
    {
        try {
            $activitiesId = ActivitySubService::where('sub_service_id', $sub_service_id)->get('activity_id');
            $activities = Activity::where('id', $activitiesId)->get();
            return response()->json([
                'data' => $activities,
                'message' => 'scusses'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'failed',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    public function show($activitiesId){
        dd($activity = Activity::find($activitiesId));

    }
}
