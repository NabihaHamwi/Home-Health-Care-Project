<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class SubService extends Model
{
    use HasFactory;

    protected $fillable = ['service_id', 'sub_service_name', 'price'];

    public function healthcareProviders()
    {
        return $this->belongsToMany(HealthcareProvider::class, 'healthcare_provider_sub_service');
    }
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activities_sub_service');
    }
}
