<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Models\Measurement;
use App\Models\Activity;
use App\Models\Session;

use Illuminate\Http\Resources\Json\JsonResource;
//use App\Models\careprovider;
class SessionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    // public function toArray(Request $request ): array
    // {
    //     return parent::toArray($request);  // }
    public function toArray($request)
    {
        if ($request->route()->named('sessions.index')) {

            return [
                'Session_Id' => $this->session_id,
                'Duration' => $this->duration,
                'Session_Date' => $this->session_date,
            ];} 


            else if($request->route()->named('sessions.show'))
             {
                  return [
                'session_id' => $this->session_id,
                'activity_name' => $this->activity_name,
                'duration' => $this->duration ,
                'observation'=>$this->observation ,
                'date'=>$this->session_date ,
                'value'=>$this->value ,
                'time'=>$this->time 
                  
            ];
        }

            
         else if ($request->route()->named('sessions.create')) {

            return [
                'id' => $this->activity_id,
                'name' => $this->activity_name,
            ];
        } else if ($request->route()->named('sessions.summary')) {
            return [
                'id' => $this->measurments_id,
                'session_id' => $this->session_id,
                'activity_id' => $this->activity_id,
                'value' => $this->value,
                
            
            ];
        } else if ($request->route()->named('sessions.store')) {
          
            return [
                'id' => $this->measurments_id,
                'activity_id' => $this->activity_id,
                'value' => $this->value,
                'time' => $this->time,
                'session_id' => $this->session_id,
                'observation' => $this->observation,
                'start_time' => $this->start_time,
                'end_time' => $this->end_time,
                'session_date' => $this->session_date,
            ];
           



        }
    }
}
