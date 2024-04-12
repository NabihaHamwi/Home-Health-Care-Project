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
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Support\Facades\DB;

class ApiSessionController extends Controller
{
    use ApiResponseTrait;
    public function index()
    {
        //if :  يكون معرف المريض موجود لحتى يتم عرض جميع الجلسات لهذا المريض

        $sessions_view = Session::all();
        if (!$sessions_view) {
            return $this->apiResponse(' ', null, 'not found', 401);
        }
        $sessions_dates_collection = SessionResource::collection($sessions_view);
        return $this->apiResponse('index', ['sessions_dates_collection' => $sessions_dates_collection], 'ok', 201);
    }





    //_________________________________________________________________



    public function show($session_id)
    {
        $session = Session::where('session_id', $session_id)->first();

        if (!$session) {
            return $this->apiResponse(' ', null, 'Session not found', 404);
        }
        $session_activity = DB::table('measurements')
            ->join('activities', 'measurements.activity_id', '=', 'activities.activity_id')
            ->where('measurements.session_id', $session->session_id)
            ->select('activities.*', 'measurements.*')
            ->get();
        if (!$session_activity) {
            return $this->apiResponse(' ', null, 'Session not found', 404);
        }
        return $this->apiResponse('show',['session_activity' => $session_activity, 'session' => $session], 'ok', 404);
    }




    public function create()
    {
        // استرجاع لانشطة الجلسة
        $sessionactivities = Activity::all();
        // :التحقق من عدم وجود اخطاء, في حال وجود خطأ 
        if (!$sessionactivities) {

            return $this->apiResponse(' ', null, 'Bad request', 401);
        } else {
            // الفكرة انو كان بدي عالج قصة انو اعرض الجلسة حسب اختصاص مقدم الرعاية
            //  لارسال عدة سطور من قاعدة البيانات(collection)استرجاع مجموعة من البيانات , استخدمنا  
            $activity_by_careprovider = SessionResource::collection($sessionactivities);
            return $this->apiResponse('create', ['activity_by_careprovider' => $activity_by_careprovider], 'ok', 201);
        }
    }

    public function session_summary($session_id)
    {
        //بحال الجلسة ليست غير موجودى بقاعدة البيانات
        $sessionrow = Session::where('session_id', $session_id)->first();

        if (!$sessionrow) {
            return $this->apiResponse(' ', null, 'not found', 401);
        }
        //استرجاع قياسات الانشطة التي قام مقدم الرعاية بالادخال اليها
        $sessionactivities = DB::table('measurements')
            ->join('activities', 'measurements.activity_id', '=', 'activities.activity_id')
            ->where('measurements.session_id', $sessionrow->session_id)
            ->select('activities.*', 'measurements.*')->get();

        if (!$sessionactivities) {
            return $this->apiResponse(' ', null, 'not found', 401);
        }
        $sessionactivities = SessionResource::collection($sessionactivities);
        return $this->apiResponse('session_summary', ['activitymeasurements' => $sessionactivities, 'sessions' => $sessionrow], 'ok', 201);
    }
   public function store(Request $request)
{
    //التحقق من ادخال البيانات 
    $validator = Validator::make($request->all(), [
        'value' => 'required|max:255',
        'time' => 'required',
        'observation' => 'required'
    ]);

    // في حال عدم وجودها ارسال رسالة الخطأ
    if ($validator->fails()) {
        return $this->apiResponse(' ', null,  $validator->errors(), 400);
    }

    $sessionmeasurements = $request->all();

    $measurement = Measurement::create($sessionmeasurements);
    $sessionobservations = Session::create($sessionmeasurements); 

    if (!($measurement &&  $sessionobservations)) {
        return $this->apiResponse(' ', null, 'the operation was not completed', 400);
    }

    $session_observations =  new SessionResource($sessionobservations);
    $session_measurements = new SessionResource($measurement);
    return $this->apiResponse('store', ['sessionmeasurements' => $sessionmeasurements, 'session_observations' => $session_observations], 'ok', 201);
}

}
