<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalTrait extends Model
{
    use HasFactory;
    public function healthcareProviders()
    {
        return $this->belongsToMany(HealthcareProvider::class);
    }
}
