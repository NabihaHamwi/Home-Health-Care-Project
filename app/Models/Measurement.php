<?php

namespace App\Models;
//use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

 //   public $incrementing = true;

// protected $table = 'measurements';

    protected $fillable = ['session_id' ,'activity_id' ,'value', 'time'];


// public function sessions()
// {
//     return $this->belongsToMany(Session::class, 'measurements', 'activity_id', 'session_id');
// }

    public function getRouteKeyName()
    {
        return 'measurements_id';
    }
  


    



      
}
