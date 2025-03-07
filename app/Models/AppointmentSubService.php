<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AppointmentSubService extends Model
{
    use HasFactory;
    protected $table = 'appointment_subservice';
    protected $fillable = [
        'appointment_id',
        'sub_service_id'
    ];
}
