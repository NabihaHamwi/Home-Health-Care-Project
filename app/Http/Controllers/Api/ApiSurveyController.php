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


    public function addQuestion(Request $request)
    {
        // تحقق من وجود البيانات المطلوبة في الطلب
        $this->validate($request, [
            'section' => 'required|string',
            'question' => 'required|string',
        ]);

        // قراءة الاستبيان الحالي
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);

        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }

        // إضافة السؤال الجديد
        $surveyData[$request->section][] = $request->question;

        // حفظ الاستبيان المعدل
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        return $this->successResponse($surveyData, 'تم إضافة السؤال بنجاح');
    }
    public function updateQuestion(Request $request)
{
    // تحقق من وجود البيانات المطلوبة في الطلب
    $this->validate($request, [
        'section' => 'required|string',
        'question_key' => 'required',
        'new_question' => 'required|string',
    ]);

    // قراءة الاستبيان الحالي
    $surveyJson = file_get_contents(config_path('survey_questions.json'));
    $surveyData = json_decode($surveyJson, true);

    if ($surveyData === null || !isset($surveyData[$request->section][$request->question_key])) {
        return $this->errorResponse('لا يمكن العثور على السؤال', 404);
    }

    // تحديث السؤال
    $surveyData[$request->section][$request->question_key] = $request->new_question;

    // حفظ الاستبيان المعدل
    file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    return $this->successResponse($surveyData, 'تم تحديث السؤال بنجاح');
}
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
