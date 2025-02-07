<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\HealthcareProviderSubService;
use App\Models\SubService;
use Illuminate\Support\Facades\Validator;


class HealthcareProviderSubServiceController extends Controller
{
    //store validation:
    protected function validateRequest(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'activity_name' => 'required|min:3|max:15',
            'activity_time' => 'required|date_format:H:i',
            'activity_image' => 'nullable|image|mimes:png,jpg,jpg,gif,svg|max:1024',
            'user_comment' => 'nullable|min:7|max:30',
        ]);


        if ($validator->fails()) {
            response()->json(['errors' => $validator->errors()], 400)->send();
            exit;
        }
    }
    /*********************************************************/
    public function createActivity($sub_service_id, $data)
    {
        // if (!($data->once) || !$data->every_x_day) {
        // }
        //dd($data->all());
        //dd($data->healthcare_provider_id);
        $activity = new HealthcareProviderSubService([
            'healthcare_provider_id' => $data['healthcare_provider_id'],
            'appointment_id' => $data['appointment_id'],
            'sub_service_id' => $sub_service_id,
            'activity_name' => $data['activity_name'],
            'activity_type' => $data['activity_type'],
            //'activity_date' => now()->addDay(), // تاريخ مستقبلي
            'repetition' => $data['repetition'],
            'every_x_day' => $data['every_x_day'],
            'user_comment' => $data['user_comment'],
            //'status' => $data->'not_completed',

        ]);
        $activity->save();
    }

    /***********************************************/
    protected function createMedicalAppointment($sub_service_id, $data)
    {
        return new HealthcareProviderSubService([
            'healthcare_provider_id' => $data['healthcare_provider_id'],
            'appointment_id' => $data['appointment_id'],
            'sub_service_id' => $sub_service_id,
            'activity_name' => $data['activity_name'],
            'activity_type' => $data['activity_type'],
            'activity_date' => $data['activity_date'], // تاريخ الموعد
            'activity_time' => $data['activity_time'],
            'user_comment' => $data['user_comment'],
            //'status' => 'not_completed',
        ]);
        $activity->save();
    }
    /*********************************************************************/

    public function storeActivities(Request $request)
    {
        //$this->validateRequest($request);

        $sub_service_ids = $request->input('sub_service_ids');
        // dd($sub_service_ids);
        $subServices = SubService::whereIn('id', $sub_service_ids)->get();
        //dd($subService);
        $data = [
            'healthcare_provider_id' => $request->input('healthcare_provider_id'),
            'appointment_id' => $request->input('appointment_id'),
            'activity_name' => $request->input('activity_name'),
            'activity_type' => $request->input('activity_type'),
            'activity_time' => $request->input('activitt_time'),
            'activity_date' => $request->input('activity_date'),
            'repetition' => $request->input('repetition'),
            'every_x_day' => $request->input('every_x_day'),
            'user_comment' => $request->input('user_comment')
        ];
        // dd($data);
        //$this->processSubService($subServices['0'], $data);

        foreach ($subServices as $subService) {
            $this->processSubService($subService, $data);
        }

        //return response()->json(['message' => 'Activities stored successfully'], 200);
    }


    // دالة لمعالجة كل خدمة فرعية
    protected function processSubService($subService, $data)
    {
        //dd($subService->is_multi_activity);
        if ($subService->is_multi_activity) {
            if ($data['activity_type'] == 'activity') {
                $activity = $this->createActivity($subService->id, $data);
            } elseif ($data['activity_type'] == 'medical_appointment') {
                $activity = $this->createMedicalAppointment($subService->id, $data);
            }
        } else {
            return response()->json(['message' => 'لا يمكنك انشاء نشاط جديد'])->send();
        }
    }


    /*********************************************************************/
}
