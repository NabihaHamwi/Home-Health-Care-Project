<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class ActivityAppointment extends Pivot
{
    use HasFactory;

    protected $table = 'activity_appointment';
    public function activity()
    {
        return $this->belongsTo(Activity::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
  
    public function activityDetails()
    {
        return $this->belongsToMany(ActivityDetail::class, 'activity_details_frequencies', 'activity_appointment_id', 'activity_detail_id');
    }
}
