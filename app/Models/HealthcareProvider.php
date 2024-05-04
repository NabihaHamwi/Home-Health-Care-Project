<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcareProvider extends Model
{
    use HasFactory;

    public function user(){ //user_id in healthcare_providers table => names is important
        return $this -> belongsTo(User::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class);
    }

    public function appointments()
    {
        // العلاقة one-to-many مع Appointment
        return $this->hasMany(Appointment::class);
    }


    // public function sessions()
    // {
    //     // العلاقة one-to-many مع Session من خلال Appointment
    //     return $this->hasManyThrough(Session::class, Appointment::class);
    // }
    
    // public function activities()
    // {
    //     // العلاقة many-to-many مع Activity من خلال Session
    //     return $this->sessions()
    //                 ->join('activity_session', 'sessions.id', '=', 'activity_session.session_id')
    //                 ->join('activities', 'activity_session.activity_id', '=', 'activities.id')
    //                 ->select('activities.*', 'sessions.date as session_date');
    // }

    // public function allActivitiesWithSessions()
    // {
    //     // استعلام للأنشطة وجلساتها مع تاريخ الجلسة
    //     return $this->activities()
    //                 ->where('activities.flag', 0)
    //                 ->orWhere('activities.flag', $this->specialty_flag)
    //                 ->get();
    // }
    
    

    










}
