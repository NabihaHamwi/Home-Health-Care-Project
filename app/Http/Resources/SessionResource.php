<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\Session;

use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\careprovider;
class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if ($request->route()->named('sessions.index')) {
            return [
                'patient_name' => $this->appointment->patient->full_name,
                //هكذا يتم الوصول لتاريخ الموعد من جدول الجلسة
                //appointment : هو اسم العلاقة المعرفة بالمودل
                'appointment_date' => $this->appointment->appointment_date,
                'observation' => $this->observation,
                'start_time' => $this->start_time,

            ];
        } else if ($request->route()->named('sessions.patientsession')) {

            foreach ($this->sessions as $session) {
                $sessionsDetails[] = [
                    'observation' => $session->observation,
                    'start_time' => $session->start_time,
                    'end_time' => $session->end_time,
                    'duration' => $session->duration,
                ];
            }

            return [
                'appointment_date' => $this->appointment_date,
                'sessions' => $sessionsDetails,
            ];
        } else if ($request->route()->named('sessions.show')) {
            //    (sessions) نحاول الوصول إلى الخصائص (النشاط) في الكائن الحالي 
            $activitiesInfo = [];
            foreach ($this->activities as $activity) {
                $activitiesInfo[] = [
                    'name' => $activity->activity_name,
                    'value' => $activity->pivot->value,
                    'time' => $activity->pivot->time,
                ];
            }

            return [
                'appointment_date' => $this->appointment->appointment_date,
                'session_start_time' => $this->start_time,
                'session_observation' => $this->observation,
                // 'activities' => ActivityResource::collection($this->activities),
                'activities' => $activitiesInfo,
            ];
        } else if ($request->route()->named('sessions.summary')) {
            foreach ($this->activities as $activity) {
                $activitiesInfo[] = [
                    'name' => $activity->activity_name,
                    'value' => $activity->pivot->value,
                    'time' => $activity->pivot->time,
                ];
            }

            return [
                'appointment_date' => $this->appointment->appointment_date,
                'session_start_time' => $this->start_time,
                // 'session_duration' => $this->duration,
                'session_observation' => $this->observation,
                'activities' => $activitiesInfo,
            ];
        } else if ($request->route()->named('sessions.store')) {
            foreach ($this->activities as $activity) {
                $activities[] = [
                    'id' => $activity->id,
                    'value' => $activity->pivot->value,
                    'time' => $activity->pivot->time,
                ];
            }

            return [
                'id' => $this->id,
                //   'appointment_date' => $this->appointment->appointment_date, // إضافة تاريخ الموعد
                'start_time' => $this->start_time,
                'observation' => $this->observation,
                'duration' => $this->duration,
                'activities' => $activities,

            ];
        } else if ($request->route()->named('sessions.edit')) {


            //    (sessions) نحاول الوصول إلى الخصائص (النشاط) في الكائن الحالي   
            $activitiesInfo = [];
            foreach ($this->activities as $activity) {
                $activitiesInfo[] = [
                    'name' => $activity->activity_name,
                    'value' => $activity->pivot->value,
                    'time' => $activity->pivot->time,
                ];
            }
            return [
                'appointment_date' => $this->appointment->appointment_date,
                'session_start_time' => $this->start_time,
                'session_observation' => $this->observation,
                'activities' => $activitiesInfo,
            ];
        } else if ($request->route()->named('sessions.update')) {
            $activities  = [];
            foreach ($this->activities as $activity) {
                $activities[] = [
                    'id' => $activity->id,
                    'value' => $activity->pivot->value,
                    'time' => $activity->pivot->time,
                ];
            }

            return [
                'id' => $this->id,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'observation' => $this->observation,
                'duration' => $this->duration,
                'appointment_date' => $this->appointment->appointment_date,
                'activities' => $activities,
            ];
        }
    }
}
