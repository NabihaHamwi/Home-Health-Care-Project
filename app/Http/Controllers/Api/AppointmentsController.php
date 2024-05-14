<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentsController extends Controller
{
    public function show_available_days(){
        $today = Carbon::now();
        $day = new Carbon();
        $day->setDate(2024, 5, 4)->locale('en_US');
        $startOfWeek = $day->startOfWeek()->format('Y-m-d');
        $endOfWeek = $day->endOfWeek()->format('Y-m-d');
        
        $reserved_days = Appointment::whereBetween('appointment_date', [$startOfWeek, $endOfWeek])->get();
        @dd($endOfWeek);
        return ;
    }
}
