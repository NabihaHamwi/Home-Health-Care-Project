<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Http\Resources\PatientAgentResource;
use Carbon\Carbon;
use Illuminate\Support\Facades\Validator;

class PatientAgentController extends Controller
{

    //show agent patients
    public function getPatients($userid)
    {
        $patients = Patient::where('user_id', $userid)->get();

        if ($patients->isEmpty()) {
            return response()->json([
                'message' => 'User has no relatives'
            ], 404);
        }
        return response()->json([
            'data' => PatientAgentResource::collection($patients),
            'status' => 200,
            'message' => 'success'
        ]);
    }


    /*****************************************************/

    public function addPatient(request $request, $userid)
    {
        //Is the patient really there?
        $existingPatient = Patient::where('national_number', $request->national_number)->first();
        if ($existingPatient) {
            return response()->json([
                'status' => '409',
                'message' => 'The patient is already there'
            ]);
        }

        // Input verification
        $validator = Validator::make(
            $request->all(),
            [
                'national_number' => 'required',
                'full_name' => 'required|min:3|max:10',
                'gender' => 'required|in:أنثى,ذكر',
                'birth_date' => 'date|date_format:d/m/Y',
                'relationship_status' => 'required|in:أعزب,متزوج,مطلق,أرمل',
                'address' => 'required|max:255',
                'phone_number' => ['required', 'regex:/^(00963|\+963)?\d{9}$/'],
                'weight' => 'required|numeric|min:1|max:130',
                'height' => 'required|numeric|min:20|max:220',
                'previous_diseases_surgeries' => 'min:3|max:200',
                'chronic_diseases' => 'min:3|max:200',
                'allergies' => 'string|min:3|max:100',
                'smoker' => 'required',
            ],
            [
                'national_number.required' => 'يرجى إدخال الرقم الوطني للمريض.',
                'full_name.required' => 'يرجى إدخال الاسم الكامل.',
                'full_name.min' => 'هل أنت متأكد من أنك قد أدخلت اسمك بشكل صحيح؟',
                'full_name.max' => 'هل أنت متأكد من أنك قد أدخلت اسمك بشكل صحيح؟',
                'gender.required' => 'يرجى تحديد الجنس.',
                'gender.in' => 'الرجاء اختيار الجنس من القائمة المحددة.',
                'birth_date.required' => 'يرجى ملء تاريخ الميلاد.',
                'birth_date.date' => 'الرجاء التأكد من صحة تاريخ الميلاد المدخل.',
                'birth_date.date_format' => 'الرجاء التأكد من صحة تاريخ الميلاد',
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
                'height.min' => 'الطول المدخل أقل من الحد الأدنى  .',
                'height.max' => 'الطول المدخل يتجاوز الحد الأقصى  .',
                'chronic_diseases.max' => 'الرجاء التأكد من أن المعلومات المدخلة ضمن الحد المسموح.',
                'allergies.string' => 'الرجاء التأكد من أن المعلومات المدخلة نصية.',
                'allergies.min' => 'الحساسية المدخلة أقل من الحد الأدنى المسموح به.',
                'allergies.max' => 'الحساسية المدخلة تتجاوز الحد الأقصى المسموح به.',
                'smoker.required' => 'يرجى تحديد ما إذا كنت مدخنًا أم لا.',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }
        $birth_date = $request->birth_date;
        $birth_date = Carbon::createFromFormat('d/m/Y', $request->birth_date)->format('Y-m-d');

        //create patient object
        $patient = new Patient;

        //patient info storage
        $patient->national_number = $request->national_number;
        $patient->user_id = $userid;
        $patient->full_name = $request->full_name;
        $patient->gender = $request->gender;
        $patient->birth_date = $birth_date;
        $patient->relationship_status = $request->relationship_status;
        $patient->address = $request->address;
        $patient->phone_number = $request->phone_number;
        $patient->weight = $request->weight;
        $patient->height = $request->height;
        $patient->previous_diseases_surgeries = $request->previous_diseases_surgeries;
        $patient->chronic_diseases = $request->chronic_diseases;
        $patient->allergies = $request->allergies;
        $patient->smoker = $request->smoker;
        $patient->created_at = now();

        $patient->save();
        return response()->json([
            'status' => '200',
            'message' => 'Patient created successfully'
        ]);
    }
}
