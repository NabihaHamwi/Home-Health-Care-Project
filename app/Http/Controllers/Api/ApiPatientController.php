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
// public $object= new ApiPatientController(); 
    //add Patient 
    public function store(request $request)
    {
        // فحص إذا كان المريض موجود بالفعل في قاعدة البيانات
        $existingPatient = Patient::where('full_name', $request->full_name)->first();
        if ($existingPatient) {
            return $this->errorResponse('المريض لديه سجل بالفعل.', 409); // كود الحالة 409 يعني "Conflict"
        }
        $validator = Validator::make(
            $request->all(),
            [
                'full_name' => 'required|min:3|max:10',
                'gender' => 'required|in:أنثى,ذكر',
                'birth_date' => 'date',
                'relationship_status' => 'required|in:أعزب,متزوج,مطلق,أرمل',
                'address' => 'required|max:255',
                'phone_number' => ['required', 'regex:/^(00963|\+963)?\d{9}$/'],
                'weight' => 'required|numeric|min:1|max:130',
                'height' => 'required|numeric|min:20|max:220',
                'chronic_diseases' => 'required|min:3|max:500',
                'allergies' => 'required|string|min:3|max:500',
                'smoker' => 'required',
            ],
            [
                'full_name.required' => 'يرجى إدخال الاسم الكامل.',
                'full_name.min' => 'هل أنت متأكد من أنك قد أدخلت اسمك بشكل صحيح؟',
                'full_name.max' => 'هل أنت متأكد من أنك قد أدخلت اسمك بشكل صحيح؟',
                'gender.required' => 'يرجى تحديد الجنس.',
                'gender.in' => 'الرجاء اختيار الجنس من القائمة المحددة.',
                'birth_date.required' => 'يرجى ملء تاريخ الميلاد.',
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
            $patient->user_id = $request->user_id;
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

            return $this->successResponse('Patient created successfully', 201);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        }
    }
    //_________________________________________________________________
   
   
    //التعديل على معلومات المريض
    public function update(Request $request, $sessionId)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'address' => 'required|max:255',
                'phone_number' => ['required', 'regex:/^(00963|\+963)?\d{9}$/'],
                'weight' => 'required|numeric|min:1.5|max:130',
                'height' => 'required|numeric|min:20|max:220',
                'chronic_diseases' => 'required|min:3|max:500',
                'allergies' => 'required|string|min:3|max:500',
                'smoker' => 'required',
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
                'chronic_diseases.required' => 'يرجى إدخال الأمراض المزمنة .',
                'chronic_diseases.max' => 'الرجاء التأكد من أن المعلومات المدخلة ضمن الحد المسموح.',
                'allergies.required' => 'يرجى إدخال الحساسية .',
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
            //  $patientupdate = Patient::findOrFail($sessionId);

            return $this->successResponse(new PatientResource($patient), 'Patient updated successfully', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorResponse('Patient not found', 404);
        } catch (\Exception $e) {
            return $this->errorResponse('Error occurred while updating the patient', 500);
        }
    }
    //______________________________________________________________________

    //عرض مرضى المستخدم
    public function show($userid)
    {
        $patients = Patient::where('user_id', $userid)->get(['id', 'full_name']);
        return response()->json([
            $patients,
            'success' => true,
        ], 200);
    }
}
