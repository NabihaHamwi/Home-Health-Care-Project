<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class HealthcareProviderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('search.result'))
            {
                $skills = $this->skills;
            return [
                'id' => $this->id,
                'name'=> $this->user->first_name,
                // 'skills'=>$skills,
                'experience' => $this->experience
            ];
        }
    }
}
