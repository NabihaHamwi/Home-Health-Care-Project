<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Activity extends Model
{
    use HasFactory;

    // علاقة many-to-many مع جدول الجلسات (sessions)
    public function sessions()
    {
        return $this->belongsToMany(Session::class)
            ->withPivot('value', 'time');
    }
    //_________________________________________________
  
    public function flags()
    {
        return $this->hasMany(ActivityFlag::class);
    }
}
