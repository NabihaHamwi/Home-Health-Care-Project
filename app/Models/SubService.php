<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubService extends Model
{
    use HasFactory;
    
    protected $fillable = ['service_id', 'sub_service_name', 'price'];

    public function healthcareProviders()
    {
        return $this->belongsToMany(HealthcareProvider::class, 'healthcare_provider_sub_service');
    }
}
