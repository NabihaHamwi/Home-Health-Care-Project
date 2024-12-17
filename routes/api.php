<?php

use App\Http\Controllers\Api\ApiSessionController as ApiSessionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ApiSurveyController;
use App\Http\Controllers\Api\ApiPatientController;
use App\Http\Controllers\Api\AppointmentsController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HealthcareProviderWorktimeController;
use App\Http\Controllers\Api\HealthcareProviderController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SubServiceController;
use App\Http\Controllers\ServiceController as ControllersServiceController;
use App\Http\Controllers\SessionController;
use App\Models\HealthcareProviderWorktime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use PHPUnit\Framework\Attributes\Group;

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
        //   Route::get('/sessions', [ApiSessionController::class, 'index'])->name('sessions.index');
        //عرض جميع جلسات المريض
        //    Route::get('/sessions/patientsessoins/{patient}', [ApiSessionController::class, 'patientSessions'])->name('sessions.patientsession');

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++ 
        // انشاء جلسة
        Route::get('/sessions/create/{appointment}', [ApiSessionController::class, 'create'])->name('sessions.create');
        //عرض جلسة للمريض
        Route::get('/sessions/{session}', [ApiSessionController::class, 'show'])->name('sessions.show');
        //عرض لوحة المتابعة 
        Route::get('sessions/panel/{patient_id}', [ApiSessionController::class, 'monitoring_panel'])->name('sessions.panel');
        //تخزين بيانات جلسة
        Route::post('/sessions', [ApiSessionController::class, 'store'])->name('sessions.store');
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++

        //عرض واجهة التعديل على الجلسة
        // Route::get('sessions/{session}/edit', [ApiSessionController::class, 'edit'])->name('sessions.edit');


        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //تحديث بيانات الجلسة
        Route::put('sessions/{session}', [ApiSessionController::class, 'update'])->name('sessions.update');
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++


        //حذف بيانات الجلسة 
        // Route::delete('/sessions/{session}', [ApiSessionController::class, 'destroy'])->name('sessions.destroy');
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
        Route::post('/survey/add-question', [ApiSurveyController::class, 'addQuestions'])->name('survey.add');
        // التعديل على سؤال من قبل الادمن
        Route::put('/survey/update-question', [ApiSurveyController::class, 'updateQuestions'])->name('survey.update');
        //حذف سؤال من الاستبيان من قبل الادمن
        Route::delete('/survey/delete-question',  [ApiSurveyController::class, 'deleteQuestions'])->name('survey.delete');
    }
);


//___________________________________________________________________


//عرض معلومات المريض
Route::group(
    [],
    function ($router) {
        //عرض جميع المرضى (بعض المعلومات) للادمن
        Route::get('/patients', [ApiPatientController::class, 'index'])->name('patients.index');
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        //عرض معلومات السجل الطبي لمريض معين
        Route::get('/patients/{patient}', [ApiPatientController::class, 'show'])->name('patients.show');
        //انشاء مريض من فبل المستخدم
        Route::post('/patients', [ApiPatientController::class, 'store'])->name('patients.store');
        //تحديث المعلومات 
        Route::put('/patients/{patient}', [ApiPatientController::class, 'update'])->name('patients.update');
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

        //  واجهة عرض معلومات المريض قبل التعديل
        //  Route::get('/patients/{patient}/edit', [ApiPatientController::class, 'edit'])->name('patients.edit');

    }
);



//___________________________________________________________________


Route::group(
    [],
    function ($router) {
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // عرض ايام عمل مقدم الرعاية
        Route::get('/careprovidersworktimes/{careproviderworktimes}', [HealthcareProviderWorktimeController::class, 'show'])->name('careprovidersworktimes.show');
        // تعبئة ايام العمل
        //  Route::post('/careprovidersworktimes', [HealthcareProviderWorktimeController::class, 'store'])->name('careprovidersworktimes.store');
        // تحديث بيانات ايام العمل
        Route::put('/careprovidersworktimes/{careproviderworktimes}', [HealthcareProviderWorktimeController::class, 'store_update'])->name('careprovidersworktimes.store_update');
        // حذف ايام العمل وإعادة تعبئتها من جديد
        Route::delete('/careprovidersworktimes/{careproviderworktimes}', [HealthcareProviderWorktimeController::class, 'destroy'])->name('careprovidersworktimes.destroy');
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    }
);




//_____________________________________________________________________

//Route::get('/register', [AuthController::class, 'register']);


Route::group([], function ($router) {
    Route::get('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/logout', [AuthController::class, 'logout']);
   // Route::post('/refresh', [AuthController::class, 'refreshToken']);
    //  Route::get('/user-profile', [AuthController::class, 'userProfile']);
});

//___________________________________________________________________________














// Api for search page
Route::get('/search', [SearchController::class, 'index'])->name(name: 'search.index');

//Api for show all services
Route::get('/services', [ServiceController::class, 'index'])->name(name: 'services.index');

//Api for the result of search (care providers)
Route::get('/search/result', [SearchController::class, 'search1'])->name(name: 'search.result');

//Api for show all providers (just for testing)
Route::get('/providers', [HealthcareProviderController::class, 'index'])->name(name: 'providers.index');

//Api for show info about one care provider
Route::get('/providers/{provider}', [HealthcareProviderController::class, 'show'])->name(name: 'providers.show');

//this api is not working yet we have the logic in front side
//Api for show the available days in week
//Route::get('/available-days/{provider}', [AppointmentsController::class, 'show_available_days'])->name(name: 'appointment.show_available_days');

//Api for show the pending appointments (for care providers) for a one week
Route::get('/pending-appointments/{provider}', [AppointmentsController::class, 'show_pending_appointments'])->name(name: 'appointments.show_pending_appointments');

//Api for show one of pending appointments details
Route::get('/pending-appointment-details/{appointment}/{group?}', [AppointmentsController::class, 'show_pending_appointments_details'])->name(name: 'appointments.show_pending_appointment');

//Api for show the reserved appointments (for care providers) for a one week
Route::get('/reserved-appointments/{provider}/{week}', [AppointmentsController::class, 'show_reserved_appointments'])->name(name: 'appointments.show_reserved_appointments');

//Api for show one of reserved appointments
// Route::get('/reserved-appointments/{appointment}', [AppointmentsController::class, 'show_appointment'])->name(name: 'appointment.show_reserved_appointment');

//Api for set appointment status
Route::put('/set_appointment_status/{appointment}/{group?}', [AppointmentsController::class, 'update'])->name(name: 'appointments.update');

//Api for reserve an appointment
Route::post('/appointments', [AppointmentsController::class, 'store'])->name(name: 'appointments.store');

//Api for show appoitments (my appointments) for patient
Route::get('/my_appointments/{patient}', [AppointmentsController::class, 'show_my_appointments'])->name(name: 'appointments.show_my_appointments');

//Api for show all Subservices
Route::get('/subservices', [SubServiceController::class, 'index'])->name(name: 'subservices.index');

//Api for show all Subservices under spesific Service
Route::get('/subservices/{service}', [SubServiceController::class, 'show'])->name(name: 'subservices.show');
