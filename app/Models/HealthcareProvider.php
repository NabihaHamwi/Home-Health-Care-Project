<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthcareProvider extends Model
{
    use HasFactory;

    public function user()
    { //user_id in healthcare_providers table => names is important
        return $this->belongsTo(User::class);
    }

    public function personaltraits()
    {
        return $this->belongsToMany(PersonalTrait::class);
    }

    public function services()
    {
        return $this->belongsToMany(Service::class)->withPivot('subservice_name');
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


    public function emergencies():HasMany
    {
        return $this->hasMany(Emergency::class);
    }
}
