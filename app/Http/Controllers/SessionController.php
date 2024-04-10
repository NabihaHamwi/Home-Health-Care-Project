<?php

namespace App\Http\Controllers;
use project\resources\views\edit;

use Illuminate\Http\Request;

class SessionController extends Controller
{
  public function index()
  { 

     return "hello world";
    
  }
public function show()
{

    return "session show ";
}
public function create(){
return view('sessions.create');
}

    public function store()
    {
       // 1. get the session data
         $data = request()->all();
         $title = request()->title/*the name (input) in html*/; 
         dd($title);
       // 2. store session data in database
         
       // 3. redirection sessions.index.  
          return to_route('sessions.index');
    }
    public function edit(){
        //select of db
        return view('sessions.edit');
    }
     //edit on session
    public function update()
    {
       //1.get the session data
       //2.
       //3.redirction sessios.show
    }
}
