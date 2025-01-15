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
    //     public function activities()
    // {
    //     return $this->hasManyThrough(
    //         Activity::class, //الجدول الهدف
    //         Session::class, //الجدول الوسيط
    //         'appointment_id', // Foreign key on the sessions table...
    //         'id', // Foreign key on the activities table...
    //         'id', // Local key on the appointments table...
    //         'activity_id' // Local key on the sessions table...
    //     );
    // }


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

    public function calculateAppointmentEndTime()
    {
        $start_time = Carbon::createFromFormat('H:i:s', $this->appointment_start_time);
        //appointmet duration is time
        $duration = Carbon::createFromFormat('H:i:s' , $this->appointment_duration);
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
