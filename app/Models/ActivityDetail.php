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
        'sub_activity_date',
        'user_comment',
        'sub_activity_time',
        'repetition',
        'every_x_day'
    ];
}
