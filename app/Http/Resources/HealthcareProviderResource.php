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
            return [
                'id' => $this->id,
                'name'=> $this->user->name,
                'experience' => $this->experience
            ];
    }
}
