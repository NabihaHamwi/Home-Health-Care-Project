// HealthcareProviderWorktimeResource.php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class HealthcareProviderWorktimeResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'provider_id' => $this->provider_id,
            'day' => $this->day,
            'start_time' => $this->start_time,
            'end_time' => $this->end_time,
        ];
    }
}
