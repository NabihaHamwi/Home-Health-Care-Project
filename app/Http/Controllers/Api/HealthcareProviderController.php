<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\HealthcareProviderResource;
use App\Models\Appointment;
use App\Models\Emergency;
use App\Models\HealthcareProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class HealthcareProviderController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        // $healthcareproviders = HealthcareProviderResource::collection();
        $providers = HealthcareProviderResource::collection(HealthcareProvider::all());
        // @dd($providers);   
        if ($providers->isEmpty()) {
            $response = [
                'msg' => 'providers not found',
                'status' => 404,
                'data' => null,
            ];
        } else {
            $response = [
                'msg' => 'providers found',
                'status' => 200,
                'data' => $providers,
            ];
        }
        return response($response);
    }
/********************************************************/

// public function show($provider_id)
// {
//     try {
//         // استرجاع معلومات مقدم الرعاية
//         $provider = HealthcareProvider::findOrFail($provider_id);

//         // استرجاع مسار الصورة الشخصية
//         $personal_image_path = $provider->personal_image;

//         // التحقق من وجود الصورة
//         if ($personal_image_path && Storage::disk('public')->exists($personal_image_path)) {
//             $image_path = storage_path('app/public/' . $personal_image_path);
//             $image_content = file_get_contents($image_path);
//             $image_type = mime_content_type($image_path);
//         } else {
//             $image_content = null;
//             $image_type = null;
//         }

//         // استرجاع المعلومات واستجابة JSON
//         $data = [
//             'user_id' => $provider->user_id,
//             'national_number' => $provider->national_number,
//             'age' => $provider->age,
//             'relationship_status' => $provider->relationship_status,
//             'experience' => $provider->experience,
//             'license_number' => $provider->license_number,
//             'personal_image' => $personal_image_path ? url('storage/' . $personal_image_path) : null,
//         ];

//         // إذا لم يكن هناك صورة، قم بإرجاع المعلومات فقط
//         if (!$image_content) {
//             return response()->json([
//                 'data' => $data,
//                 'message' => 'success'
//             ]);
//         }

//         // إرجاع المعلومات والصورة
//         return response()
//             ->make($image_content, 200)
//             ->header('Content-Type', $image_type)
//             ->header('Content-Disposition', 'inline; filename="' . basename($personal_image_path) . '"');

//     } catch (\Exception $e) {
//         return response()->json([
//             'message' => 'failed',
//             'error' => $e->getMessage()
//         ], 500);
//     }
// }

    //     public function updateProviderCache($providerId, $isAvailable, $latitude = null, $longitude = null, $locationName = null)
    //     {
    //         $cacheKey = 'provider_status_' . $providerId;
    //         $providerData = Cache::get($cacheKey);

    //         if (!$providerData) {
    //             // إذا لم تكن البيانات موجودة في الكاش، نقوم بجلبها من قاعدة البيانات
    //             $providerData = HealthcareProvider::find($providerId);
    //         }

    //         if ($providerData) {
    //             $providerData->is_available = $isAvailable;
    //             $providerData->latitude = $latitude ?? $providerData->latitude;
    //             $providerData->longitude = $longitude ?? $providerData->longitude;
    //             $providerData->location_name = $locationName ?? $providerData->location_name;
    //             $providerData->updated_at = now();

    //             // تحديث الكاش
    //             Cache::put($cacheKey, $providerData, now()->addMinutes(10));

    //             return response()->json(['message' => 'Provider status cached successfully']);
    //         }

    //         return response()->json(['message' => 'Provider not found'], 404);
    //     }


    //     public function store(request $request)
    //     {

    //         $provideravaliabilty = HealthcareProvider::where('is_available', true)->get();
    //     }
    /* **************************************************** */


    public function isAvailableUpdate(Request $request, HealthcareProvider $healthcareProvider)
    {
        dd($providerId = $healthcareProvider->id);
        // $cacheKey = 'provider_status_' . $providerId;
        // $providerData = Cache::get($cacheKey);

        // if (!$providerData) {
        //     // إذا لم تكن البيانات موجودة في الكاش، نقوم بجلبها من قاعدة البيانات
        //     $providerData = HealthcareProvider::find($providerId);
        // }
        //dd($providerData);
        //تخزين القيمة الأصلية لحالة مقدم الرعاية 
        $originalAvailability = $healthcareProvider->is_available;
        if ($originalAvailability) {
            return response()->json(['message' => 'مقدم الرعاية بالأصل متاح'], 200);
        }
        // تخزين معلومات الطلب بمصفوفة
        $data = $request->all();
        //تعبئة معلومات المصفوفة بجدول مقدم الرعاية لكن لم يتم حفظ التغيرات
        $healthcareProvider->fill($data);
        // dd($healthcareProvider->is_available);
        // dd(!$originalAvailability);
        if ($this->hasOngoingAppointmentsToday($healthcareProvider) && $healthcareProvider->is_available && !$originalAvailability) {
            return response()->json(['message' => 'لا يمكن تغيير الحالة إلى متاح لأن مقدم الرعاية لديه موعد .'], 400);
        } else if ($healthcareProvider->is_available && !$originalAvailability) {
            $healthcareProvider->save();
            return response()->json(['message' => 'تمت العملية بنجاح']);
        }
    }

    public function hasOngoingAppointmentsToday(HealthcareProvider $healthcareProvider): bool
    {
        $today = Carbon::today('Asia/Damascus');
        $appointments = $healthcareProvider->appointments()
            ->whereDate('appointment_date', $today)->whereIn('appointment_status', ['الطلب مقبول'])
            ->get();

        foreach ($appointments as $appointment) {
            $endTime = $appointment->calculateAppointmentEndTime1();
            //dd(Carbon::now());
            //dd(Carbon::now()->locale('en_US'));
            //dd(Carbon::now('Asia/Damascus')->between($appointment->appointment_start_time, $endTime));
            // echo $appointment;
            // اذا كان الوقت الحالي يقع بين وقتين 
            if (Carbon::now('Asia/Damascus')->between($appointment->appointment_start_time, $endTime)) {
                return true;
            }
        }

        $latestAppointmentEmergency = $healthcareProvider->emergencies()
            ->whereDate('care_appointment_date', $today)
            ->whereIn('appointment_status', ['scheduled', 'in_progress'])
            ->latest('care_appointment_date')
            ->first();
        //dd($latestAppointmentEmergency);
        //dd(!is_null($latestAppointmentEmergency));
        return !is_null($latestAppointmentEmergency);
    }
    // public function patientSupserviced(HealthcareProvider $healthcareprovider)
    // {
    //     dd($healthcareprovider);
    //     dd($providerId = $healthcareprovider->provider_id);
    //     dd($getPatient = Appointment::where('provider_id', $providerId)->get('patient_id'));
    // }
}
