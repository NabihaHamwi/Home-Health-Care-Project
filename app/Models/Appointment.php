<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Appointment extends Model
{
    use HasFactory;

    protected $fillable = [
        'group_id',
        'patient_id',
        'healthcare_provider_id',
        'service_id',
        'appointment_date',
        'appointment_start_time',
        'appointment_duration',
        'patient_location',
        'appointment_status',
        'caregiver_status'
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function healthcare_provider()
    { //healthcare_provider_id in healthcare_providers table => names is important
        return $this->belongsTo(HealthcareProvider::class);
    }

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }

    public function service()
    {
        return $this->belongsTo(Service::class);
    }

    public function sub_service()
    {
        return $this->belongsToMany(SubService::class);
    }

    // public function activitydetails()
    // {
    //     return $this->belongsToMany(ActivityDetail::class ,'activity_detail_appointment');
    // }
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_appointment')->withPivot('id');
    }

    public function calculateAppointmentEndTime()
    {
        $start_time = Carbon::createFromFormat('H:i:s', $this->appointment_start_time);
        //appointmet duration is time
        $duration = Carbon::createFromFormat('H:i:s', $this->appointment_duration);
        //add duration to appointment_start_time
        $end_time = $start_time->copy()->addHours($duration->hour)->addMinutes($duration->minute)->addSeconds($duration->second);
        // dd($end_time); 
        return $end_time->format('H:i:s');
    }
    public function calculateAppointmentEndTime1()
    {
        try {
            // وقت بداية الموعد
            $start_time = Carbon::createFromFormat('H:i:s', $this->appointment_start_time);

            // مدة الموعد (time)
            $duration = Carbon::createFromFormat('H:i:s', $this->appointment_duration);

            // إضافة مدة الموعد إلى وقت البداية
            $end_time = $start_time->copy()->addHours($duration->hour)->addMinutes($duration->minute)->addSeconds($duration->second);
            // dd($end_time);
            return $end_time->format('H:i:s');
        } catch (\Exception $e) {
            return 'Invalid time format';
        }
    }
}
