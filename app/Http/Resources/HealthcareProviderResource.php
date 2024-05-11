<?php

namespace App\Http\Resources;

use App\Models\HealthcareProvider;
use App\Models\Skill;
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
        $skills = SkillResource::collection(HealthcareProvider::find($this->id)->skills);
        $services = ServiceResource::collection(HealthcareProvider::find($this->id)->services);
        if ($request->route()->named('search.result'))
            return [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'age' => $this->age,
                'experience' => $this->experience,
                'services' => $services
            ];
        else if ($request->route()->named('providers.index'))
            return [
                'id' => $this->id,
                'user_details' => $this->user,
                'gender' => $this->gender,
                'relationship_status' => $this->relationship_status,
                'age' => $this->age,
                'experience' => $this->experience,
                'personal_image' => $this->personal_image,
                'physical_strength' => $this->physical_strength,
                'min_working_hours_per_day' => $this->min_working_hours_per_day,
                'services' => $services,
                'skills' => $skills
            ];
        else if ($request->route()->named('providers.show'))
            return [
                'id' => $this->id,
                'first_name' => $this->user->first_name,
                'last_name' => $this->user->last_name,
                'gender' => $this->gender,
                'age' => $this->age,
                'experience' => $this->experience,
                'personal_image' => $this->personal_image,
                'physical_strength' => $this->physical_strength,
                'services' => $services,
                'skills' => $skills
            ];
    }
}
