<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use HasFactory;
    public function subServices()
    {
        return $this->belongsToMany(SubService::class, 'activity_sub_service');
    }
  
    // public function activityAppointments()
    // {
    //     return $this->hasMany(ActivityAppointment::class);
    // }

    public function appointments()
    {
        return $this->belongsToMany(Appointment::class);
    }
}

