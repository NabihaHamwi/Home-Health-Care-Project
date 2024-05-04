<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('search.index'))
            return [
                'id' => $this->id,
                'name' => $this->name
            ];
        else if ($request->route()->named('services.index'))
            return [
                'id' => $this->id,
                'name' => $this->name,
                'description' => $this->description
            ];
        return ['not found route name'];
    }
}
