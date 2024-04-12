<?php

namespace App\http\Controllers\Api;

trait ApiResponseTrait
{
    public function apiResponse($type, $data = null, $msg = null, $status = null)

    {
        // دالة نخزن فيها جميع القيم (رسالة الخطأ, الحالة , البيانات )
        $response = [
            'msg' => $msg,
            'status' => $status
        ];

        switch ($type) {
            case 'index':
                $response['sessions_dates_collection'] = $data['sessions_dates_collection'];


                break;
            case 'show': // بحال كان الطلب
                $response['session_activity'] = $data['session_activity'];
                $response['session'] = $data['session'];

                break;


            case 'create':
                $response['activity_by_careprovider'] = $data['activity_by_careprovider'];

                break;
            case 'session_summary': // بحال كان الطلب
                // مصفوفة لح ترجع قياسات جميع الانشطة
                $response['activitymeasurements'] = $data['activitymeasurements'];
                //لح ترجع بيانات الجلسة (الملاحظات و معرف الجلسة ووقت الجلسة)
                $response['sessions'] = $data['sessions'];
                break;
            case 'store':
                $response['sessionmeasurements'] = $data['sessionmeasurements'];
                //  $response['sessionobservations'] = $data['sessionobservations'];
                break;
        }
        return response($response);
    }
}
