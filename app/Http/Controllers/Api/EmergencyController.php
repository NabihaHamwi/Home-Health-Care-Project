<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Emergency;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\HealthcareProviderController;
use App\Models\HealthcareProvider;
//use GuzzleHttp\Client;

class EmergencyController extends Controller
{
//     public function caregiverfilter() {}

//     public function search(Request $request)
//     {
//         $lat = $request->input('lat');
//         $lng = $request->input('lng');
//         $service = $request->input('service');
//         $gender = $request->input('gender');
//         $strength = $request->input('strength');
//         $radius = 2000; // 2 كيلومتر
//         $maxTravelTime = 30 * 60; // 30 دقيقة

//         // البحث عن مقدمي الرعاية المتاحين والقريبين
//         $careProviders = HealthcareProvider::where('available', true)
//             ->where('service', $service)
//             ->when($gender, function ($query, $gender) {
//                 return $query->where('gender', $gender);
//             })
//             ->when($strength, function ($query, $strength) {
//                 return $query->where('strength', '>=', $strength);
//             })
//             ->get();

//         // فلترة مقدمي الرعاية بناءً على المسافة
//         $filteredProviders = $careProviders->filter(function ($provider) use ($lat, $lng, $radius, $maxTravelTime) {
//             $distance = $this->calculateDistance($lat, $lng, $provider->lat, $provider->lng);
//             $travelTime = $this->calculateTravelTime($distance);

//             return $distance <= $radius && $travelTime <= $maxTravelTime;
//         });

//         return response()->json($filteredProviders);
//     }

   
  
//         public function calculateDistanceAndTime(Request $request)
//         {
//             $origins = $request->input('origins'); // إحداثيات المريض
//             $destinations = $request->input('destinations'); // إحداثيات مقدمي الرعاية
//             $apiKey = 'AlzaSy-LMGxk97cd0Zc_eI7HvRLSF6aOAb7XHRc';
    
//             $client = new Client();
//             $response = $client->get('https://maps.gomaps.pro/maps/api/directions/json', [
//                 'query' => [
//                     'origins' => $origins,
//                     'destinations' => $destinations,
//                     'key' => $apiKey
//                 ]
//             ]);
    
//             $data = json_decode($response->getBody(), true);
    
//             return response()->json($data);
//         }
    
    
//     private function calculateTravelTime($distance)
//     {
//         $averageSpeed = 40; // كم/ساعة (يمكنك تعديلها حسب الاحتياج)
//         $travelTime = ($distance / 1000) / $averageSpeed * 3600;

//         return $travelTime;
//     }
 }
