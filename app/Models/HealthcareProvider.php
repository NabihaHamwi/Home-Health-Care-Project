<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthcareProvider extends Model
{
    use HasFactory;

    protected $fillable = ['user_id','national_number' ,'age','relationship_status','experience','personal_image','license_number','min_working_hours_per_day','is_available', 'location_name', 'latitude', 'longitude'];

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

    public function subservices()
    {
        return $this->hasMany(HealthcareProviderService::class);
    }


    public function emergencies(): HasMany
    {
        return $this->hasMany(Emergency::class);
    }
    public function documents():HasMany
    {
        return $this->hasMany(Document::class);
    }
}
