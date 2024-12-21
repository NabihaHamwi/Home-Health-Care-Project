<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;



    public function sessions()
    {
        return $this->hasManyThrough(Session::class, Appointment::class);
    }



    //_____________________________________________



    public function healthcareProviders()
    {
        return $this->belongsToMany(HealthcareProvider::class)->withPivot('subservice_name');
    }



    //_______________________________________________



    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_service');
    }
}
