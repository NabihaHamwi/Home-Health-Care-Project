<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\careprovider;
class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request): array
    // {
    //     return parent::toArray($request);
    // }

    //     public function toArray(Request $request): array
    // {
    //     foreach ($this-> data as $item) {
    //         $data = [
    //             'id' => $item->session_id,
    //             'duration' => $item->duration,
    //         ];
    //     }

    //     return $data;
    // }


    // public function toArray(Request $request)
    // {

    //         $data = [
    //             'id' =>$this-> session_id,
    //             'duration'=>$this -> duration,
    //         ];


    //     return response($data);
    // }
    public function toArray($request)
    {
        if ($request->route()->named('sessions.show')) {
            $sessions_data = [
                'session_id' => $this->session_id,
                'duration' => $this->duration,
                'observation' => $this->observation,

            ];
            $activities_data = [
                'activities' => $this->activity_name
            ];

            $measurements_data = [
                'value' => $this->value,
                'time' => $this->time
            ];

            return [
                'measurements' => $measurements_data,
                'activities' => $activities_data,
                'sessions'   => $sessions_data
            ];
        }

        else if ($request->route()->named('sessions.create')) {
            // if('')
            return [
                'id' => $this->activity_id,
                'name' => $this->activity_name,
                
            ];
        }

    else if ($request->route()->named('sessions.store')) {
        return [
            'id' => $this->measurments_id,
            'value' => $this->value,
            'time' => $this -> time
        ];
    }
    else if ($request->route()->named('sessions.edit')) {
      
    }
    else if ($request->route()->named('sessions.update')) {
      
    }
   

}
}
