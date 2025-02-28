<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthcareProviderWorktimeResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('careprovidersworktimes.show') || $request->route()->named('appointment.show_available_days')) {
            return [
                'day_name' => $this->day_name,
                'work_hours' => $this->work_hours,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,



            ];
        } else if ($request->route()->named('careprovidersworktimes.store')) {

            return [
                'day_name' => $this->day_name,
                'work_hours' => $this->work_hours,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ];
        }else if ($request->route()->named('careprovidersworktimes.showday')) {

            return [
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
            ];
        }
    }
}
