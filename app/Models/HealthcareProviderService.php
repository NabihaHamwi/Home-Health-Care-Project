<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HealthcareProviderService extends Model
{
    use HasFactory;
    protected $table = 'healthcare_provider_service';

    public function emergecies(): HasMany
    {
        return $this->hasMany(Emergency::class);
    }
}
