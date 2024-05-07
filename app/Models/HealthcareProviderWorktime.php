<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthcareProviderWorktime extends Model
{
    use HasFactory;

    public $timestamps = true;

    protected $fillable = ['start_time', 'work_hours', 'end_time', 'healthcare_provider_id' , 'day_name'];


    public function provider()
    {
        return $this->belongsTo(HealthcareProvider::class, 'healthcare_provider_id');
    }











}
