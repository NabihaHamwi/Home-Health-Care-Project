<?php

namespace App\Http\Controllers;

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
   public function show(Session $session)
   {
    //  $activity = Activity::all();
//$measurements = Measurement::where('session_id', $session->session_id)->get();
    //  return view('sessions.show', ['sessionrow' => $session], ['measurementssession' => $measurements], ['activitiesnames' => $activity]);
   }

   public function create()
   {
      return view('sessions.create');
   }

   public function store()
   {  
      return to_route('sessions.index');
   }
   public function edit(Session $session)
   {
      //select of db
      // $activity = Activity::all();
      // $measurements = Measurement::where('session_id', $session->session_id)->get();
      // return view('sessions.edit', ['sessionid' => $session], ['act' => $activity]);
   }
   //edit on session
   public function update()
   {
      
   }
}
