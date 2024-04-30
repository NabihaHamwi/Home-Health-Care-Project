<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;


    public function activities()
    {
        // تحديد الأعمدة الإضافية 'value' و 'time'
        return $this->belongsToMany(Activity::class, 'activity_session')
                    ->withPivot('value', 'time');
    }
    

}
