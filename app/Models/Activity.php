<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use HasFactory;
    
//     public function sessions():BelongsToMany
// {
//     return $this->belongsToMany(Session::class, 'measurements', 'activity_id', 'session_id');
// }

    
    public function getRouteKeyName()
    {
        return 'actvity_id';
    }

// public function measurements()
// {
//     return $this->belongsToMany(Measurement::class, 'measurements', 'activity_id', 'session_id');
// }

    }
    



