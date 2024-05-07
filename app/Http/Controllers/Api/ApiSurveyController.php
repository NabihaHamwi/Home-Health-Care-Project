<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ApiSurveyController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        // تأكد من تحديد المسار الصحيح لملف JSON
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);

        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }

        return $this->successResponse($surveyData, 'ok');
    }

    //___________________________________________________________________


    public function addQuestions(Request $request)
    {
        // تحقق من وجود البيانات المطلوبة في الطلب
        $this->validate($request, [
            'section' => 'required|string',
            'questions' => 'required|array',
            'questions.*' => 'required|string',
        ]);
    
        // قراءة الاستبيان الحالي
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);
    
        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }
    
        // إضافة الأسئلة الجديدة
        foreach ($request->questions as $question) {
            $surveyData[$request->section][] = $question;
        }
    
        // حفظ الاستبيان المعدل
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
        return $this->successResponse($surveyData, 'تم إضافة الأسئلة بنجاح');
    }
    

    //___________________________________________________________________________________



    public function updateQuestions(Request $request)
    {
        // تحقق من وجود البيانات المطلوبة في الطلب
        $this->validate($request, [
            'section' => 'required|string',
            'questions' => 'required|array',
            'questions.*.key' => 'required',
            'questions.*.new_question' => 'required|string',
        ]);
    
        // قراءة الاستبيان الحالي
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);
    
        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }
    
        // تحديث الأسئلة
        foreach ($request->questions as $questionData) {
            $key = $questionData['key'];
            if (isset($surveyData[$request->section][$key])) {
                $surveyData[$request->section][$key] = $questionData['new_question'];
            }
        }
    
        // حفظ الاستبيان المعدل
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
        return $this->successResponse($surveyData, 'تم تحديث الأسئلة بنجاح');
    }

    

//_______________________________________________________________________________________



public function deleteQuestion(Request $request)
{
    // تحقق من وجود البيانات المطلوبة في الطلب
    $this->validate($request, [
        'section' => 'required|string',
        'question_key' => 'required',
    ]);

    // قراءة الاستبيان الحالي
    $surveyJson = file_get_contents(config_path('survey_questions.json'));
    $surveyData = json_decode($surveyJson, true);

    if ($surveyData === null || !isset($surveyData[$request->section][$request->question_key])) {
        return $this->errorResponse('لا يمكن العثور على السؤال لحذفه', 404);
    }

    // حذف السؤال
    unset($surveyData[$request->section][$request->question_key]);

    // إعادة ترتيب المفاتيح في حالة الأسئلة المرقمة
    $surveyData[$request->section] = array_values($surveyData[$request->section]);

    // حفظ الاستبيان بعد الحذف
    file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    return $this->successResponse($surveyData, 'تم حذف السؤال بنجاح');
}


}
