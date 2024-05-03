<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    use HasFactory;

    // العلاقة many-to-one مع Service
    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }


//     // العلاقة many-to-many مع Activity من خلال Session
//     public function activities()
//     { // للحصول الانشطة المرتبطة بالجلسات
//         return $this->sessions()
//                     ->join('activity_session', 'sessions.id', '=', 'activity_session.session_id')
//                     ->join('activities', 'activity_session.activity_id', '=', 'activities.id')
//                     ->select('activities.*', 'sessions.date as session_date');
//     }

//     // استعلام للأنشطة المشتركة والخاصة بالخدمة من خلال الجلسات
//     public function allActivitiesWithSessions()
//     {
//         return $this->activities()
//                     ->where('activities.flag', 'shared')
//                     ->orWhere('activities.flag', $this->service_id)
//                     ->get();
//     }

// 
}
