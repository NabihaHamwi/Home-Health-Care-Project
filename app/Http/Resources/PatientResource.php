<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;

use App\Models\Activity;
use App\Models\Session;

use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\careprovider;
class PatientResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request)
    {
        if ($request->route()->named('patients.index')) {
            return [
                'full_name' => $this->full_name,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'created_at' => $this->created_at,
                'updated_at' => $this->updated_at,

            ];
        } else if ($request->route()->named('patients.show')) {

            return $this->filterNullValues();
        }
        // else if($request->reoute()->named('patients.store')){


        // }
        else if ($request->route()->named('patients.edit')) {

            return $this->filterNullValues();
        } else if ($request->route()->named('patients.update')) {
            return $this->filterNullValues();
        } 
    }








    protected function filterNullValues()
    {
        return array_filter($this->resource->toArray(), function ($value) {
            return !is_null($value);
        });
    }
}
