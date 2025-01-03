<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Patient extends Model
{
    use HasFactory;
    
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function emergencies():HasMany
    {
        return $this->hasMany(Emergency::class);
    }
}
