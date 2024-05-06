<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Http\Resources\PatientResource;
use Illuminate\Support\Facades\Validator;

class ApiPatientController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        try {
            $patients = Patient::paginate(10);
            if (!$patients) {
                return $this->errorResponse('not found', 404);
            }
            return $this->successResponse(PatientResource::collection($patients), 'Sessions retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }


    //___________________________________________________________________



    public function show($sessionId)
    {
        try {
            $patient = Patient::findOrFail($sessionId);
            return $this->successResponse(new PatientResource($patient), 'Session retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }

    //______________________________________________________________


    public function edit($sessionId)
    {

        try {
            $patient = Patient::findOrFail($sessionId);
            return $this->successResponse(new PatientResource($patient), 'Session retrieved successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Illuminate\Database\QueryException $e) {
            return $this->errorResponse('Error querying the database', 500);
        }
    }


    //_________________________________________________________________
    public function store(request $request)
    {

        $validator = Validator::make(
            $request->all(),
            [
                'full_name' => 'required|min:3|max:10',
                'gender' => 'required|in:male,female,other',
                'birth_date' => 'required|date',
                'relationship_status' => 'required|in:single,married,divorced,widowed',
                'address' => 'required|max:255',
                'phone_number' => ['required', 'regex:/^(00963|\+963)?\d{9}$/'],
                'weight' => 'required|numeric|min:1.5|max:130',
                'height' => 'required|numeric|min:20|max:220',
                'chronic_diseases' => 'required|min:5|max:500',
                'allergies' => 'required|string|min:5|max:500',
                'smoker' => 'required|boolean',
            ],
            [
                'full_name.required' => 'يرجى إدخال الاسم الكامل.',
                'full_name.min' => 'يجب ألا يقل الاسم الكامل عن 3 أحرف.',
                'full_name.max' => 'يجب ألا يزيد الاسم الكامل عن 10 أحرف.',
                'gender.required' => 'يرجى تحديد الجنس.',
                'gender.in' => 'الرجاء اختيار الجنس من القائمة المحددة.',
                'birth_date.required' => 'يرجى إدخال تاريخ الميلاد.',
                'birth_date.date' => 'الرجاء التأكد من صحة تاريخ الميلاد المدخل.',
                'relationship_status.required' => 'يرجى تحديد الحالة الاجتماعية.',
                'address.required' => 'يرجى إدخال العنوان.',
                'address.max' => 'العنوان المدخل طويل جدًا.',
                'phone_number.required' => 'يرجى إدخال رقم الهاتف.',
                'phone_number.regex' => 'الرجاء التحقق من صحة رقم الهاتف المدخل.',
                'weight.required' => 'يرجى إدخال الوزن.',
                'weight.numeric' => 'الرجاء التأكد من أن الوزن المدخل عبارة عن رقم.',
                'weight.min' => 'الوزن المدخل أقل من الحد الأدنى المسموح به.',
                'weight.max' => 'الوزن المدخل يتجاوز الحد الأقصى المسموح به.',
                'height.required' => 'يرجى إدخال الطول.',
                'height.numeric' => 'الرجاء التأكد من أن الطول المدخل عبارة عن رقم.',
                'height.min' => 'الطول المدخل أقل من الحد الأدنى المسموح به.',
                'height.max' => 'الطول المدخل يتجاوز الحد الأقصى المسموح به.',
                'chronic_diseases.required' => 'يرجى إدخال الأمراض المزمنة إن وجدت.',
                'chronic_diseases.max' => 'الرجاء التأكد من أن المعلومات المدخلة ضمن الحد المسموح.',
                'allergies.required' => 'يرجى إدخال الحساسية إن وجدت.',
                'allergies.string' => 'الرجاء التأكد من أن المعلومات المدخلة نصية.',
                'allergies.min' => 'الحساسية المدخلة أقل من الحد الأدنى المسموح به.',
                'allergies.max' => 'الحساسية المدخلة تتجاوز الحد الأقصى المسموح به.',
                'smoker.required' => 'يرجى تحديد ما إذا كنت مدخنًا أم لا.',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        try {
            // إنشاء مريض جديد
            $patient = new Patient;

            // تعيين معلومات المريض من الطلب
            $patient->full_name = $request->full_name;
            $patient->gender = $request->gender;
            $patient->birth_date = $request->birth_date;
            $patient->relationship_status = $request->relationship_status;
            $patient->address = $request->address;
            $patient->phone_number = $request->phone_number;
            $patient->weight = $request->weight;
            $patient->height = $request->height;
            $patient->previous_diseases_surgeries = $request->previous_diseases_surgeries;
            $patient->chronic_diseases = $request->chronic_diseases;
            $patient->current_medications = $request->current_medications;
            $patient->allergies = $request->allergies;
            $patient->family_medical_history = $request->family_medical_history;
            $patient->smoker = $request->smoker;
            $patient->addiction = $request->addiction;
            $patient->exercise_frequency = $request->exercise_frequency;
            $patient->diet_description = $request->diet_description;
            $patient->current_symptoms = $request->current_symptoms;
            $patient->recent_vaccinations = $request->recent_vaccinations;
            $patient->created_at = now();

            $patient->save();

            return $this->successResponse(new PatientResource($patient), 'Patient created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorResponse('Error occurred while creating the patient', 500);
        }
    }


    public function update(Request $request, $sessionId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'birth_date' => 'required|date',
                'relationship_status' => 'required|in:single,married,divorced,widowed',
                'address' => 'required|max:255',
                'phone_number' => ['required', 'regex:/^(00963|\+963)?\d{9}$/'],
                'weight' => 'required|numeric|min:1.5|max:130',
                'height' => 'required|numeric|min:20|max:220',
                'chronic_diseases' => 'required|min:5|max:500',
                'allergies' => 'required|string|min:5|max:500',
                'smoker' => 'required|boolean',
            ],
            [
                'relationship_status.required' => 'يرجى تحديد الحالة الاجتماعية.',
                'address.required' => 'يرجى إدخال العنوان.',
                'address.max' => 'العنوان المدخل طويل جدًا.',
                'phone_number.required' => 'يرجى إدخال رقم الهاتف.',
                'phone_number.regex' => 'الرجاء التحقق من صحة رقم الهاتف المدخل.',
                'weight.required' => 'يرجى إدخال الوزن.',
                'weight.numeric' => 'الرجاء التأكد من أن الوزن المدخل عبارة عن رقم.',
                'weight.min' => 'الوزن المدخل أقل من الحد الأدنى المسموح به.',
                'weight.max' => 'الوزن المدخل يتجاوز الحد الأقصى المسموح به.',
                'height.required' => 'يرجى إدخال الطول.',
                'height.numeric' => 'الرجاء التأكد من أن الطول المدخل عبارة عن رقم.',
                'height.min' => 'الطول المدخل أقل من الحد الأدنى المسموح به.',
                'height.max' => 'الطول المدخل يتجاوز الحد الأقصى المسموح به.',
                'chronic_diseases.required' => 'يرجى إدخال الأمراض المزمنة إن وجدت.',
                'chronic_diseases.max' => 'الرجاء التأكد من أن المعلومات المدخلة ضمن الحد المسموح.',
                'allergies.required' => 'يرجى إدخال الحساسية إن وجدت.',
                'allergies.string' => 'الرجاء التأكد من أن المعلومات المدخلة نصية.',
                'allergies.min' => 'الحساسية المدخلة أقل من الحد الأدنى المسموح به.',
                'allergies.max' => 'الحساسية المدخلة تتجاوز الحد الأقصى المسموح به.',
                'smoker.required' => 'يرجى تحديد ما إذا كنت مدخنًا أم لا.',

            ],

        );
        // في حال عدم وجودها ارسال رسالة الخطأ
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        try {
            $patient = Patient::findOrFail($sessionId);

            // تحديث معلومات المريض
            //  $patient->full_name = $request->full_name;
            //   $patient->gender = $request->gender;
            //      $patient->birth_date = $request->birth_date;
            $patient->relationship_status = $request->relationship_status;
            $patient->address = $request->address;
            $patient->phone_number = $request->phone_number;
            $patient->weight = $request->weight;
            $patient->height = $request->height;
            $patient->previous_diseases_surgeries = $request->previous_diseases_surgeries;
            $patient->chronic_diseases = $request->chronic_diseases;
            $patient->current_medications = $request->current_medications;
            $patient->allergies = $request->allergies;
            $patient->family_medical_history = $request->family_medical_history;
            $patient->smoker = $request->smoker;
            $patient->addiction = $request->addiction;
            $patient->exercise_frequency = $request->exercise_frequency;
            $patient->diet_description = $request->diet_description;
            $patient->current_symptoms = $request->current_symptoms;
            $patient->recent_vaccinations = $request->recent_vaccinations;
            $patient->updated_at = now();

            $patient->save();

            return $this->successResponse(new PatientResource($patient), 'Patient updated successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Error occurred while updating the patient', 500);
        }
    }
}
