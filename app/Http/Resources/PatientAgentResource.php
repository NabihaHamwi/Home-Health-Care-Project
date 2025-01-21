<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\Session;

use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\careprovider;
class PatientAgentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if ($request->route()->named('patients.getPatients')) {
            return [
                'id' => $this->id,
                'full_name' => $this->full_name,
            ];
        }
    }
}