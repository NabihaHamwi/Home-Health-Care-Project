<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Measurement extends Model
{
    use HasFactory;

    protected $fillable = ['value', 'time'];

    public function getRouteKeyName()
    {
        return 'measurements_id';
    }
}
