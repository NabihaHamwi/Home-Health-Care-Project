<?php
namespace App\http\Controllers\Api;

trait ApiResponseTrait
{
    public function apiResponse($type, $data = null, $msg = null, $status = null)

    { 
        return response($data);
     
        {
            $response = [
                'msg' => $msg,
                'status' => $status
            ];
        
            switch ($type) {
                case 'show':
                    $response['activitymeasure'] = $data['activitymeasure'];
                    $response['sessions'] = $data['sessions'];
                    return 0;
                    break;
                case 'create':
                    $response['activities'] = $data['activities'];
                    break;
            }
        
            return response($response);
        }
        



 }
}
