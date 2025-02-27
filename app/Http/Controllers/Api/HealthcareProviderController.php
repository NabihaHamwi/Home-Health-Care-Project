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

class HealthcareProviderController extends UserController
{
    use ApiResponseTrait;


    // get all providers
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
    /************************************************************************************/

    //show info about one care provider
    public function show($provider_id)
    {
        try { // الدالة (findOrFail) بترمي استثناء ولكن لازم حدا يلتقطه ويعالجه وهي الدالة (catch)
            $provider = HealthcareProvider::findOrFail($provider_id);
            return $this->successResponse(new HealthcareProviderResource($provider), 'Provider details retrieved successfully');
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Provider not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('erorr query', 500);
        }
    }

    /********************************************************************************/
    // update careproviders availability

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
    /******************************************************************************/

    // update careproviders availability

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

    /******************************************************************************/
    
           
}
