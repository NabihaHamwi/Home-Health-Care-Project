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
        if ($request->route()->named('appointments.show_pending_appointments'))
            return [
                "group_id" => $this->group_id,
                "appointment_id" => $this->id,
                "patient_name" => $this->patient->full_name,
                "service_name" => $this->service->name
            ];
        if ($request->route()->named('appointments.show_reserved_appointments') || $request->route()->named('appointments.show_pending_appointment')) {
            $patientfile = [
                'previous_diseases_surgeries' => $this->patient->previous_diseases_surgeries,
                'chronic_diseases' => $this->patient->chronic_diseases,
                'current_medications' => $this->patient->current_medications,
                'allergies' => $this->patient->allergies,
                'family_medical_history' => $this->patient->family_medical_history,
                'smoker' => $this->patient->smoker,
                'addiction' => $this->patient->addiction,
                'exercise_frequency' => $this->patient->exercise_frequency,
                'diet_description' => $this->patient->diet_description,
                'current_symptoms' => $this->patient->current_symptoms,
                'recent_vaccinations' => $this->patient->recent_vaccinations,
            ];
            return [
                "appointment_id" => $this->id,
                "patient_name" => $this->patient->full_name,
                "patient_file" => $patientfile,
                "appointment_date" => $this->appointment_date,
                "appointment_start_at" => $this->appointment_start_time,
                "appointment_duration" => $this->appointment_duration,
                "service_name" => $this->service->name
            ];
        }
        if ($request->route()->named('appointments.update'))
            return [
                "appointment_id" => $this->id,
                "appointment_date" => $this->appointment_date,
                "appointment_start_at" => $this->appointment_start_time,
                "appointment_duration" => $this->appointment_duration,
                "appointment_status" => $this->appointment_status
            ];

        if ($request->route()->named('appointments.show_my_appointments'))
            return [
                "appointment_id" => $this->id,
                "appointment_date" => $this->appointment_date,
                "appointment_start_at" => $this->appointment_start_time,
                "appointment_duration" => $this->appointment_duration,
                "appointment_status" => $this->appointment_status,
                "service_name" => $this->service->name,
                "provider_name"=> $this->healthcare_provider->user->first_name ." ". $this->healthcare_provider->user->last_name,
            ];
        // if ($request->route()->named('appointment.show_reserved_appointment')) {
        //     $patientfile = [
        //         'previous_diseases_surgeries' => $this->patient->previous_diseases_surgeries,
        //         'chronic_diseases' => $this->patient->chronic_diseases,
        //         'current_medications' => $this->patient->current_medications,
        //         'allergies' => $this->patient->allergies,
        //         'family_medical_history' => $this->patient->family_medical_history,
        //         'smoker' => $this->patient->smoker,
        //         'addiction' => $this->patient->addiction,
        //         'exercise_frequency' => $this->patient->exercise_frequency,
        //         'diet_description' => $this->patient->diet_description,
        //         'current_symptoms' => $this->patient->current_symptoms,
        //         'recent_vaccinations' => $this->patient->recent_vaccinations,
        //     ];
        //     return [
        //         "appointment_id" => $this->id,
        //         "patient_name" => $this->patient->full_name,
        //         "patient_file" => $patientfile,
        //         "appointment_date" => $this->appointment_date,
        //         "appointment_start_at" => $this->appointment_start_time,
        //         "appointment_duration" => $this->appointment_duration
        //     ];
        // }
        // if ($request->route()->named('appointment.show_pending_appointment'))
        //     return [
        //         "appointment_id" => $this->id,
        //         "patient_name" => $this->patient->full_name,
        //         "appointment_date" => $this->appointment_date,
        //         "appointment_start_at" => $this->appointment_start_time,
        //         "appointment_duration" => $this->appointment_duration
        //     ];
    }
}
