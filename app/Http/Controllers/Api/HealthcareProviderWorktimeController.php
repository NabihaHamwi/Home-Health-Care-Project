<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\HealthcareProviderWorktime;
use App\Http\Resources\HealthcareProviderWorktimeResource;
use Illuminate\Support\Facades\Validator;
use App\Models\HealthcareProvider;

class HealthcareProviderWorktimeController extends Controller
{
    use ApiResponseTrait;

    public function show($healthcare_provider_id)
    {
        try {
            $worktimes = HealthcareProviderWorktime::where("healthcare_provider_id", $healthcare_provider_id)->get();
            if ($worktimes->isEmpty()) {
                return $this->errorResponse('worktimes not found', 404);
            }
            return $this->successResponse(HealthcareProviderWorktimeResource::collection($worktimes), 'operation accomplished successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('worktimes not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }



    //_____________________________________________________________________________






    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'worktimes' => 'required|array',
            'worktimes.*.healthcare_provider_id' => 'required|integer|exists:healthcare_providers,id',
            'worktimes.*.day_name' => 'required|string',
            'worktimes.*.start_time' => 'sometimes|date_format:H:i|required_without:worktimes.*.work_hours',
            'worktimes.*.end_time' => 'sometimes|date_format:H:i|required_without:worktimes.*.work_hours',
            'worktimes.*.work_hours' => 'sometimes|numeric|required_without_all:worktimes.*.start_time,worktimes.*.end_time',
        ]);
    
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        foreach ($request->worktimes as $worktimeData) {
            // تحقق إذا تم إدخال ساعات العمل وهي تساوي 24
            if (isset($worktimeData['work_hours']) && $worktimeData['work_hours'] == 24) {
                $worktimeData['start_time'] = '00:00';
                $worktimeData['end_time'] = '23:59';
                $workHours = 24;
            } elseif (isset($worktimeData['start_time']) && isset($worktimeData['end_time'])) {
                // حساب ساعات العمل بناءً على الفرق بين وقت البداية والنهاية
                $start = strtotime($worktimeData['start_time']);
                $end = strtotime($worktimeData['end_time']);
                if ($end < $start) {
                    $end += 24 * 3600; // أضف 24 ساعة
                }
    
                $workHours = ($end - $start) / 3600;


            } else {
                return $this->errorResponse('يجب إدخال وقت البداية والنهاية أو ساعات العمل.', 422);
            }

            $caregiver = HealthcareProvider::find($worktimeData['healthcare_provider_id']);
            if ($workHours <= $caregiver->min_working_hours_per_day) {
                return $this->errorResponse('ساعات العمل أقل من الحد الأدنى لساعات العمل في اليوم.', 422);
            }

            HealthcareProviderWorktime::create([
                'healthcare_provider_id' => $worktimeData['healthcare_provider_id'],
                'day_name' => $worktimeData['day_name'],
                'work_hours' => $workHours,
                'start_time' => $worktimeData['start_time'],
                'end_time' => $worktimeData['end_time'],
            ]);
        }

        return $this->successResponse(null, 'تم تخزين بيانات أيام العمل بنجاح', 200);
    }


    //___________________________________________________________________________________




    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'worktimes' => 'required|array',
            'worktimes.*.day_name' => 'required|string',
            'worktimes.*.work_hours' => 'required|integer',
            'worktimes.*.start_time' => 'required|date_format:H:i',
            'worktimes.*.end_time' => 'required|date_format:H:i|after:worktimes.*.start_time',
        ]);

        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            foreach ($request->worktimes as $worktimeData) {
                $start = strtotime($worktimeData['start_time']);
                $end = strtotime($worktimeData['end_time']);
                $duration = ($end - $start) / 60; // حساب الفرق بالدقائق

                // التحقق من أن مدة العمل تساوي الفرق بين وقت البداية ووقت النهاية
                if ($duration !== $worktimeData['work_hours'] * 60) {
                    return $this->errorResponse('يجب أن يكون الفرق بين وقت البداية ووقت النهاية يساوي مدة العمل المحددة', 422);
                }

                $worktime = HealthcareProviderWorktime::where('healthcare_provider_id', $id)
                    ->where('day_name', $worktimeData['day_name'])
                    ->firstOrFail();

                $worktime->update([
                    'work_hours' => $duration / 60, // تحويل الدقائق إلى ساعات
                    'start_time' => $worktimeData['start_time'],
                    'end_time' => $worktimeData['end_time'],
                ]);
            }

            return $this->successResponse(null, 'تم تحديث بيانات أيام العمل بنجاح', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('worktimes not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }





    //_____________________________________________________________________________________________







    public function destroy()
    {
    }
}
