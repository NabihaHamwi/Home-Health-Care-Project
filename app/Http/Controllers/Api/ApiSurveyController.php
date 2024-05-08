<?php

namespace App\Http\Controllers\Api;

use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make(
            $request->all(),
            [
                'questions' => 'required|array',
                'questions.*.key' => 'required|distinct|filled',
                'questions.*.question' => 'required|string|filled',
            ],
            [
                'questions.required' => 'حقل الأسئلة مطلوب.',
                'questions.*.key.required' => 'مفتاح السؤال مطلوب.',
                'questions.*.key.distinct' => 'يجب أن لا يتكرر مفتاح السؤال.',
                'questions.*.key.filled' => 'لا يجب أن يكون مفتاح السؤال فارغًا.',
                'questions.*.question.required' => 'السؤال مطلوب.',
                'questions.*.question.string' => 'يجب أن يكون السؤال نصًا.',
                'questions.*.question.filled' => 'لا يجب أن يكون السؤال فارغًا.',
            ]
        );

        // إذا فشل التحقق، أرجع رسالة خطأ
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // قراءة الاستبيان الحالي
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);

        // التحقق من صحة الاستبيان
        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }

        // قائمة لتتبع المفاتيح المكررة
        $duplicateQuestions = [];

        // إضافة الأسئلة الجديدة مع التحقق من عدم وجودها مسبقًا
        foreach ($request->questions as $questionData) {
            $key = $questionData['key'];
            if (!isset($surveyData['questions'][$key])) {
                // إذا لم يكن السؤال موجودًا، أضفه إلى الاستبيان
                $surveyData['questions'][$key] = $questionData['question'];
            } else {
                // إذا كان السؤال موجودًا بالفعل، أضفه إلى قائمة الأسئلة المكررة
                $duplicateQuestions[] = $key;
            }
        }

        // التحقق من وجود أسئلة مكررة
        if (!empty($duplicateQuestions)) {
            return $this->errorResponse(['duplicate_questions' => $duplicateQuestions], 'الأسئلة التالية موجودة بالفعل.');
        }

        // حفظ الاستبيان المعدل
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // إرجاع رسالة نجاح
        return $this->successResponse($surveyData, 'تم إضافة الأسئلة بنجاح');
    }

    //___________________________________________________________________________________



    public function updateQuestions(Request $request)
    {
        // تحقق من وجود البيانات المطلوبة في الطلب
        $validator = Validator::make(
            $request->all(),
            [
                'questions' => 'required|array',
                'questions.*.key' => 'required|distinct|filled',
                'questions.*.new_question' => 'required|string|filled|regex:/\S/',
            ],
            [
                'questions.required' => 'حقل الأسئلة مطلوب.',
                'questions.array' => 'يجب أن يكون حقل الأسئلة عبارة عن مصفوفة.',
                'questions.*.key.required' => 'مفتاح السؤال مطلوب.',
                'questions.*.key.distinct' => 'يجب أن لا يتكرر مفتاح السؤال.',
                'questions.*.key.filled' => 'لا يجب أن يكون مفتاح السؤال فارغًا.',
                'questions.*.new_question.required' => 'السؤال الجديد مطلوب.',
                'questions.*.new_question.string' => 'يجب أن يكون السؤال الجديد نصًا.',
                'questions.*.new_question.filled' => 'لا يجب أن يكون السؤال الجديد فارغًا.',
                'questions.*.new_question.regex' => 'يجب أن يحتوي السؤال الجديد على أحرف غير فارغة.',
            ]
        );

        // إذا فشل التحقق، استخدم الـ trait لإرجاع الأخطاء
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // قراءة الاستبيان الحالي
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);

        // إذا كانت البيانات فارغة أو غير صالحة، استخدم الـ trait لإرجاع رسالة خطأ
        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }

        // تحديث الأسئلة
        foreach ($request->questions as $questionData) {
            $key = $questionData['key'];
            if (isset($surveyData['questions'][$key])) {
                $surveyData['questions'][$key] = $questionData['new_question'];
            } else {
                // إذا كان المفتاح غير موجود، استخدم الـ trait لإرجاع رسالة خطأ
                return $this->errorResponse("المفتاح '{$key}' غير موجود", 404);
            }
        }

        // حفظ الاستبيان المعدل
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // استخدم الـ trait لإرجاع رسالة نجاح
        return $this->successResponse($surveyData, 'تم تحديث الأسئلة بنجاح');
    }




    //_______________________________________________________________________________________


    public function deleteQuestions(Request $request)
    {
        // تحقق من وجود البيانات المطلوبة في الطلب
        // يجب أن يحتوي الطلب على مصفوفة 'question_keys'، وكل عنصر في المصفوفة يجب أن يكون مملوءًا ومميزًا
        $validator = Validator::make(
            $request->all(),
            [
                'question_keys' => 'required|array|distinct',
                'question_keys.*' => 'required|filled',
            ]
        );
        if ($validator->fails()) {
            return $this->errorResponse($validator->errors(), 422);
        }

        // قراءة الاستبيان الحالي من ملف JSON
        $surveyJson = file_get_contents(config_path('survey_questions.json'));
        $surveyData = json_decode($surveyJson, true);

        // إذا كانت البيانات فارغة أو غير صالحة، يتم إرجاع رسالة خطأ
        if ($surveyData === null) {
            return $this->errorResponse('لا يمكن قراءة الاستبيان', 404);
        }

        // تكرار عبر مصفوفة مفاتيح الأسئلة وحذف كل سؤال موجود
        foreach ($request->question_keys as $questionKey) {
            if (isset($surveyData['questions'][$questionKey])) {
                unset($surveyData['questions'][$questionKey]);
            }
        }

        // حفظ الاستبيان المعدل بعد حذف الأسئلة
        file_put_contents(config_path('survey_questions.json'), json_encode($surveyData, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

        // إرجاع رسالة نجاح تفيد بأن الأسئلة تم حذفها بنجاح
        return $this->successResponse($surveyData, 'تم حذف الأسئلة بنجاح');
    }
}
