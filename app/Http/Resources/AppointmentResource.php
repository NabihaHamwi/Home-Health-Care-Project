<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('appointment.show_pending_appointments'))
            return [
                "appointment_id" => $this->id,
                "patient_name" => $this->patient->full_name,
                "appointment_date" => $this->appointment_date,
                "appointment_start_at" => $this->appointment_start_time,
                "appointment_duration" => $this->appointment_duration
            ];
        if ($request->route()->named('appointment.reserved_days'))
            return [
                "appointment_id" => $this->id,
                "patient_name" => $this->patient->full_name,
                "appointment_date" => $this->appointment_date,
                "appointment_start_at" => $this->appointment_start_time,
                "appointment_duration" => $this->appointment_duration,
                // "service_name" => $this->service->service_name
            ];
    }
}
