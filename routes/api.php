<?php

use App\Http\Controllers\Api\ApiSessionController as ApiSessionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ApiSurveyController;
use App\Http\Controllers\Api\ApiPatientController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\ServiceController as ControllersServiceController;
use App\Http\Controllers\SessionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

// لوحة المتابعة
Route::group(
    [],
    function ($router) {
        //عرض جميع جلسات المرضى للادمن
        Route::get('/sessions', [ApiSessionController::class, 'index'])->name('sessions.index');
        //عرض جميع جلسات المريض
        Route::get('/sessions/patientsessoins/{patient}', [ApiSessionController::class, 'patientSessions'])->name('sessions.patientsession');
        //عرض جلسة للمريض
        Route::get('/sessions/{session}', [ApiSessionController::class, 'show'])->name('sessions.show');
        //عرض ملخص الجلسة
        Route::get('sessions/summary/{patient_id}', [ApiSessionController::class, 'session_summary'])->name('sessions.summary');
        // انشاء جلسة
        Route::get('/sessions/create/{appointment}', [ApiSessionController::class, 'create'])->name('sessions.create');
        Route::post('/sessions', [ApiSessionController::class, 'store'])->name('sessions.store');
        //عرض واجهة التعديل على الجلسة
        Route::get('sessions/{session}/edit', [ApiSessionController::class, 'edit'])->name('sessions.edit');
        //تحديث بيانات الجلسة
        Route::put('sessions/{session}', [ApiSessionController::class, 'update'])->name('sessions.update');
        //حذف بيانات الجلسة 
        Route::delete('/sessions/{session}', [ApiSessionController::class, 'destroy'])->name('sessions.destroy');
    }
);


//___________________________________________________________________



//عرض اسئلة الاستبيان
Route::group(
    [],
    function ($router) {
        //عرض اسئلة الاستبيان
        Route::get('/survey', [ApiSurveyController::class, 'index'])->name('survey.index');
        //اضافة سؤال جديد للاستبيان من قبل الادمن
        Route::post('/survey/add-question', [ApiSurveyController::class, 'addQuestion'])->name('survey.add');
        // التعديل على سؤال من قبل الادمن
        Route::put('/survey/update-question', [ApiSurveyController::class, 'updateQuestion'])->name('survey.update');
        //حذف سؤال من الاستبيان من قبل الادمن
        Route::delete('/survey/delete-question',  [ApiSurveyController::class, 'deleteQuestion'])->name('survey.delete');
    }
);


//___________________________________________________________________



//عرض معلومات المريض
Route::group(
    [],
    function ($router) {
        //عرض جميع المرضى (بعض المعلومات) للادمن
        Route::get('/patients', [ApiPatientController::class, 'index'])->name('patients.index');
        //عرض معلومات السجل الطبي لمريض معين
        Route::get('/patients/{patient}', [ApiPatientController::class, 'show'])->name('patients.show');
        //انشاء مريض من فبل المستخدم
        Route::post('/patients', [ApiPatientController::class, 'store'])->name('patients.store');
        //  واجهة عرض معلومات المريض قبل التعديل
        Route::get('/patients/{patient}/edit', [ApiPatientController::class, 'edit'])->name('patients.edit');
        //تحديث المعلومات 
        Route::put('/patients/{patient}', [ApiPatientController::class, 'update'])->name('patients.update');
    }
);



//___________________________________________________________________



// Api for search page
Route::get('/search', [SearchController::class, 'index'])->name(name: 'search.index');

//Api for show all services
Route::get('/services', [ServiceController::class, 'index'])->name(name: 'services.index');

//Api for the result of search (care providers)
Route::get('/search/result', [SearchController::class, 'search'])->name(name: 'search.result');


//Route::get('/providers', [HealthcareProviderController::class, 'index']) -> name(name: 'providers.index');
