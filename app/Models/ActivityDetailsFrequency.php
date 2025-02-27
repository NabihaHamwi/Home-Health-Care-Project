<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActivityDetailsFrequency extends Model
{
    use HasFactory;
    protected $table = 'activity_details_frequencies';
    protected  $fillable = [
        'activity_detail_id', 
        'activity_appointment_id',
        'start_time',
        'every_x_hours',
        'sub_activity_execution_time',
        'value',
        'provider_comment',
        'sub_activity_image',
        'status',
    ];


    public function activityDetail()
    {
        return $this->belongsTo(ActivityDetail::class, 'activity_detail_id', 'id');
    }

    public function activityAppointment()
    {
        return $this->belongsTo(ActivityAppointment::class, 'activity_appointment_id', 'id');
    }
}
