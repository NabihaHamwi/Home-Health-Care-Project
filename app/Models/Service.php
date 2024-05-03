<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    public function appointments()
{
    return $this->hasMany(Appointment::class);
}


public function sessions()
{
    return $this->hasManyThrough(Session::class, Appointment::class);
}

    public function activities()
    {
        // يجب تعريف العلاقة many-to-many هنا
        return $this->hasManyThrough(Activity::class, Appointment::class, 'service_id', 'id', 'id', 'appointment_id');
    }



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
//                 ->where('activities.flag','انشطة مشتركة')
//                 ->orWhere('activities.flag',  $this->id)
//                 ->get();

// }
}
