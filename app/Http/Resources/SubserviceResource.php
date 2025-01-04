<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubserviceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        if ($request->route()->named('subservices.index') || $request->route()->named('subservices.show') || $request->route()->named('search.result'))
            return [
                'name' => $this->subservice_name
            ];
        return parent::toArray($request);
    }
}
