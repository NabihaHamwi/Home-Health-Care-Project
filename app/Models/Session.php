<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Session extends Model
{
    use HasFactory;
//     public function activities()
// {
//     return $this->belongsToMany(Activity::class, 'measurements', 'session_id', 'activity_id');
// }

    
    protected $fillable = ['observation', 'duration'];
    public $timestamps = false;


    public function getRouteKeyName()
    {
        return 'session_id';
    }
  
        protected $attributes = [
            'start_time' => '0000-00-00 00:00:00', 
            //'end_time' => '0000-00-00 00:00:00', 
            
            // قيمة افتراضية
        ];
    }
    
    
        
    
    
