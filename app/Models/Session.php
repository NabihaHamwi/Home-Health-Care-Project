<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    // العلاقة مع جدول النشاطات (activities) تبقى كما هي
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_session')->withPivot('value', 'time');
    }
    protected static function boot()
{
    parent::boot();

    static::creating(function ($session) {
        $start = \DateTimeImmutable::createFromFormat('H:i', $session->start_time);
        $end = \DateTimeImmutable::createFromFormat('H:i', $session->end_time);

        if ($start === false || $end === false) {
            // Handle the error here.
            return;
        }

        // If the end time is before the start time, add one day to the end time.
        if ($end < $start) {
            $end = $end->modify('+1 day');
        }

        $duration = $end->getTimestamp() - $start->getTimestamp();
        
        // Convert the duration from seconds to hours.
        $session->duration = $duration / 3600;

        // Convert the time format to 12 hours.
        $session->start_time = $start->format('h:i A');
        $session->end_time = $end->format('h:i A');
    });
}

}   

