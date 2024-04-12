<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use project\resources\views\edit;
use App\Models\User;
use App\Models\Session;
use App\Models\Measurement;
use App\Models\Activity;
use Illuminate\Http\Request;

class SessionController extends Controller
{
   public function index()
   {

      return "hello world";
   }
   public function show($session_id)
   {

    $session = Session::where('session_id', $session_id)->first();

    if ($session) {
        $sessionActivity = DB::table('measurements')
            ->join('activities', 'measurements.activity_id', '=', 'activities.activity_id')
            ->where('measurements.session_id', $session->session_id)
            ->select('activities.*', 'measurements.*')
            ->get();

        dd($sessionActivity);
    
}

    
}

      
   

//    public function create()
//    {
//      // $sessionId = Session::();
//       $activity = Activity::all();
//       return view('sessions.create', ['activities' => $activity]);
//    } 
//    public function edit(Session $session)
//    {
//       //select of db
//       // $activity = Activity::all();
//       // $measurements = Measurement::where('session_id', $session->session_id)->get();
//       // return view('sessions.edit', ['sessionid' => $session], ['act' => $activity]);
//    }
//   public function session_summary($sessionId)
//   {
//    return 'hi';
//   }
//   public function store()
//   {
//    return 'test';
//   }
// }
}