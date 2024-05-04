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

    // public function patient()
    // {
    //     return $this->belongsTo(Patient::class , 'appointments');
    // }
    // العلاقة مع جدول النشاطات (activities) تبقى كما هي
    public function activities()
    {
        return $this->belongsToMany(Activity::class, 'activity_session')->withPivot('value', 'time');
        } 
        // protected static function boot()
        // {
        //     parent::boot();
        
        //     static::creating(function ($session) {
        //         // تحقق من صحة تنسيق الوقت المدخل بتنسيق 24 ساعة
        //         $start = \DateTimeImmutable::createFromFormat('H:i', $session->start_time);
        //         $end = \DateTimeImmutable::createFromFormat('H:i', $session->end_time);
        
        //         if ($start === false || $end === false) {
        //             // Handle the error here.
        //             return false; // إرجاع false لإيقاف عملية الإنشاء.
        //         }
        
        //         // إذا كان وقت النهاية قبل وقت البداية، أضف يومًا إلى وقت النهاية.
        //         if ($end < $start) {
        //             $end = $end->modify('+1 day');
        //         }
        
        //         // حساب المدة بالثواني.
        //         $duration = $end->getTimestamp() - $start->getTimestamp();
        
        //         // تحويل المدة من الثواني إلى الساعات والدقائق.
        //         $hours = intdiv($duration, 3600);
        //         $minutes = ($duration % 3600) / 60;
        
        //         // تخزين المدة بتنسيق HH:MM.
        //         $session->duration = sprintf('%02d:%02d', $hours, $minutes);
        
        //         // تخزين تنسيق الوقت بنظام 24 ساعة.
        //         $session->start_time = $start->format('H:i');
        //         $session->end_time = $end->format('H:i');
        //     });
        // }
        
}