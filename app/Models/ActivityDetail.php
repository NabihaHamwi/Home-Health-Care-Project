<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDetail extends Model
{
    use HasFactory;
    protected $fillable = [
        'activity_id',
        'appointment_id',
        'sub_activity_name',
        'sub_activity_type',
        'start_date',
        'end_date',
        'user_comment',
        'sub_activity_time',
        'repetition',
        'every_x_day',
        'repeat_count_per_day'
    ];

    public function activityDetailsFrequencies()
    {
        return $this->hasMany(ActivityDetailsFrequency::class);
    }
    public function activityAppointments()
    {
        return $this->belongsToMany(ActivityAppointment::class, 'activity_details_frequencies', 'activity_detail_id', 'activity_appointment_id');
    }
}
