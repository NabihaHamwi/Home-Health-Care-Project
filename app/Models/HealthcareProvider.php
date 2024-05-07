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

    
    public function worktimes()
    {
        return $this->hasMany(HealthcareProviderWorktime::class);
    }



    










}
