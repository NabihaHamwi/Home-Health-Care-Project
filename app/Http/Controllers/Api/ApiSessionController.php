<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\SessionResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use App\Models\Session;
use App\Models\Measurement;
use App\Models\Activity;
use Illuminate\Support\Facades\DB;

class ApiSessionController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
    }

    public function show($session_id)
    {
        $session = Session::where('session_id', $session_id)->first();
    
        if (!$session) {
            return $this->apiResponse('show', null, 'bad request', 401);
        }
    
        $sessionactivity = DB::table('measurements')
            ->join('activities', 'measurements.activity_id', '=', 'activities.activity_id')
            ->where('measurements.session_id', $session->session_id)
            ->select('activities.*', 'measurements.*')->get();
    
        if ($sessionactivity) {
            return $this->apiResponse('show', ['activitymeasurements' => $sessionactivity, 'sessions' => $session], 'ok', 201);
        }
        return $this->apiResponse('show', null, 'not found', 401);
    }
    

    public function create()
    {
        $sessionactivity = Activity::all();
        if ($sessionactivity) {
            // الفكرة انو كان بدي عالج قصة انو اعرض الجلسة حسب اختصاص مقدم الرعاية
            $activity_by_careprovider = new SessionResource($sessionactivity);
            return $this->apiResponse($activity_by_careprovider, 'ok', 201);
        } else
            return $this->apiResponse(null, null, 'Bad request', 401);
    }
    // public function create()
    // {
    //     $activities = Activity::all();
    
    //     return $this->apiResponse('create', ['activities' => $activities], 'ok', 201);
    // }
    

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'value' => 'required|max:255',
            'time' => 'required',
        ]);
        if ($validator->fails()) {
            return $this->apiResponse(null,  $validator->errors(), 400);
        }
        $measurements = Measurement::create($request->all());
        if ($measurements) {
            return $this->apiResponse(new SessionResource($measurements), 'ok', 201);
        }
    }

    public function edit(Session $session)
    {
        $activity = Activity::all();
        $measurements = Measurement::where('session_id', $session->session_id)->get();
        return view('sessions.edit', ['sessionid' => $session], ['act' => $activity]);
    }
    //edit on session
    public function update(Request $request, $postId)
    {
    }

    public function session_summary(Session $session)
    {
        $sessionvalue = Measurement::where('session_id', $session->session_id)->get();
        $sessionactivity = Activity::all();
        return $this->apiResponse($sessionvalue, $sessionactivity, $session);

        //return 'hello';  
    }
}
