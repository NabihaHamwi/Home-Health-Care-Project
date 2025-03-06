<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ActivityResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('activitysubservice.provider_activities'))
            return [
                "activity_id" => $this->id,
                "activity_name" => $this->activity_name,
            ];
    }
}
