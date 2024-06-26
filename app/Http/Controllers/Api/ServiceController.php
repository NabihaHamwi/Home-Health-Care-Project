<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ServiceResource;
use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function index()
    {
        $services = ServiceResource::collection(Service::all()); // استرجاع جميع الخدمات
        if ($services->isEmpty()) {
            $response = [
                'msg' => 'Services not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'تم استرجاع الخدمات',
                'status' => 200,
                'data' => $services,
            ];
        }
        return response($response); // إرسال الخدمات 
    }

    public function show(Request $request){
        // $service = new ServiceResource(Service::find($request)); // استرجاع الخدمة الواحدة
        // if($service){
        //     $response = [
        //         'msg' => 'service found',
        //         'status' => 200,
        //         'data' => $service,
        //     ];
        // } 
        // else{
        //     $response = [
        //         'msg' => 'Service not found',
        //         'status' => 404,
        //         'data' => null,
        //     ];
        // }
        // return response($response); // إرسال اسم و تفاصيل الخدمة
    }
}
