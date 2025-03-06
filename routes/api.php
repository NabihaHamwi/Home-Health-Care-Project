<?php

use App\Http\Controllers\Api\ActivityController;
use App\Http\Controllers\Api\ActivityDetailController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\ApiSessionController as ApiSessionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\ApiSurveyController;
use App\Http\Controllers\Api\ApiPatientController;
use App\Http\Controllers\Api\SubActivityFrequencyController;
use App\Http\Controllers\Api\AppointmentsController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\HealthcareProviderWorktimeController;
use App\Http\Controllers\Api\HealthcareProviderController;
use App\Http\Controllers\Api\SkillController;
use App\Http\Controllers\Api\SubServiceController;
use App\Http\Controllers\Api\EmergencyController;
use App\Http\Controllers\Api\HealthcareProviderSubServiceController;
use App\Http\Controllers\Api\PatientAgentController;
use App\Http\Controllers\ServiceController as ControllersServiceController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Api\ActivityAppointmentController;
use App\Http\Controllers\Api\ActivitySubServiceController;
use App\Models\Emergency;
use App\Models\HealthcareProvider;
use App\Models\HealthcareProviderSubService;
use App\Models\HealthcareProviderWorktime;
use App\Models\Patient;
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
//apis for contollpanel:
Route::group(
    [],
    function ($router) {
        //api for retrive daily-sub-activities(frequences) to today's appointment date (controll panel)
        Route::get('/daily-sub-activities', [SubActivityFrequencyController::class, 'getDailySubActivities'])->name(name: 'frequencies.getDailySubActivities'); //->middleware('auth:api');
        //Route::get('/activities/{groupId}', [HealthcareProviderSubServiceController::class, 'getActivities'])->name('activties.getActivities');
        //api for show 
        //Route::get('/activitiy/{activityId}', [HealthcareProviderSubServiceController::class, 'getDetailsActivity'])->name('activties.getDetailsActivity');
        //جاهز للربط
        // Retrieve  all sub-activities for accepted and unfinished appointment 
        Route::get('/get-all-sub-activities/appointment', [ActivityDetailController::class, 'getDetailedActivities'])->name(name: 'activitiyDetails.getDetailedActivities');
        // select appointment by group_id or appoinmtent_id
        //جاهز للربط
        Route::post('/select-Appointment', [ApiPatientController::class, 'selectGroupOrAppointment'])->name(name: 'patient.selectAppointment');
        //show activities under appointment:
        Route::get('/appointment-activities', [ActivityAppointmentController::class, 'getActivitiesAppointment'])->name(name: 'activities.getActivitiesAppointment');
        //retrieve the dates of the appointment:
        //جاهز للربط
        Route::get('/get-appointment-dates', [ActivityDetailController::class, 'getAppointmentDates'])->name(name: 'activitiyDetails.getAppointmentDates');
        //apis for store activity details and update(by careporvider on activities controll panel)
        //create one-time sub-activity:
        Route::post('/activities/single-day', [ActivityDetailController::class, 'createSingleDayActivity']);
        //create sub-activity with repetitions:
        Route::post('/store-sub-activity', [ActivityDetailController::class, 'storeSubActivity'])->name('activties.storeActivityDetails');
        //change daily sub-activity status:(update Daily progress of sub-activity)
        Route::Put('/sub-activity/daily-progress/{frequencyId}', [SubActivityFrequencyController::class, 'updaeDailySubActivityStatus'])->name(name: 'frequencies.updaeDailySubActivityStatus');
        //انه اذا كان النشاط ليس له نشاط جزئي يشكل توماتيكي النشاط الجزئي هو نفسه ولا يسمح للمستخدم بادخال نشاط جزئي
        //apis for healthcare-provider:
        Route::get('/acceptable-appointments-provider', [HealthcareProviderController::class, 'acceptable-appointmentsProvider'])->name(name: 'frequencies.getDailySubActivities');
    }
);

//___________________________________________________________________

//apis for patients
Route::group(
    [],
    function ($router) {
        //عرض معلومات السجل الطبي لمريض معين
        // Route::get('/patients/{patient}', [ApiPatientController::class, 'show'])->name('patients.show');
        //انشاء مريض من قبل المستخدم
        Route::post('/add-patient/{userid}', [PatientAgentController::class, 'addPatient'])->name('patients.addPatient');
        //تحديث المعلومات 
        // Route::put('/patients/{patient}', [ApiPatientController::class, 'update'])->name('patients.update');
        //api for show all patients 
        Route::get('/get-patients/{userid}', [PatientAgentController::class, 'getPatients'])->name(name: 'patients.getPatients');

        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++

    }
);
//___________________________________________________________________

//apis for careprovider worktimes
Route::group(
    [],
    function ($router) {
        //++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
        // عرض ايام عمل مقدم الرعاية
        Route::get('/careprovider-worktimes/{providerid}', [HealthcareProviderWorktimeController::class, 'show'])->name('careprovidersworktimes.show');
        // تحديث بيانات ايام العمل
        Route::post('/careprovider-worktimes/{providerid}', [HealthcareProviderWorktimeController::class, 'store_update'])->name('careprovidersworktimes.store_update');
        // حذف ايام العمل وإعادة تعبئتها من جديد
        Route::delete('/careprovider-worktimes/{providerid}', [HealthcareProviderWorktimeController::class, 'destroy'])->name('careprovidersworktimes.destroy');
        //+++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++++
    }
);
//_____________________________________________________________________

// apis for authentication
Route::group([], function ($router) {
    Route::post('/register', [UserController::class, 'register']);
    Route::post('/login', [UserController::class, 'login']);
    Route::post('/logout', [UserController::class, 'logout']);
});

//___________________________________________________________________________

//apis for emergencies feature
//Route::post('/update/{healthcareProvider}', [HealthcareProviderController::class, 'isAvailableUpdate'])->name(name: 'providers.isAvailableUpdate');
//Route::get('/search', [EmergencyController::class, 'calculateDistanceAndTime'])->name(name: 'emergencies.search');

//___________________________________________________________________

//api for add a careprovider
Route::post('/add-provider', [AdminController::class, 'addProvider'])->name(name: 'admin.addProvider');

//api for show user fullname
Route::get('/get-fullname/{users}', [UserController::class, 'getUserFullName'])->name(name: 'users.getUserFullName');

//api for show provider fullname + images
Route::get('/get-provider/{healthcareProvider}', [HealthcareProviderController::class, 'getProvider'])->name(name: 'provider.getprovider');
//add services and subservices to the careprovider
// Route::post('/storesubservice', [HealthcareProviderController::class, 'store'])->name(name: 'HealthcareProvider.store');



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
Route::get('/pending-appointments', [AppointmentsController::class, 'show_pending_appointments'])->name(name: 'appointments.show_pending_appointments');

//Api for show one of pending appointments details
Route::get('/pending-appointment-details/{appointment}/{group?}', [AppointmentsController::class, 'show_pending_appointments_details'])->name(name: 'appointments.show_pending_appointment');

//Api for show the reserved appointments (for care providers) for a one week
Route::get('/reserved-appointments/{date}', [AppointmentsController::class, 'show_reserved_appointments'])->name(name: 'appointments.show_reserved_appointments');

//Api for show one of reserved appointments
// Route::get('/reserved-appointments/{appointment}', [AppointmentsController::class, 'show_appointment'])->name(name: 'appointment.show_reserved_appointment');

//Api for set appointment status
Route::put('/set_appointment_status/{appointment}/{group?}', [AppointmentsController::class, 'update'])->name(name: 'appointments.update');

//Api for reserve an appointment
Route::post('/appointments', [AppointmentsController::class, 'store'])->name(name: 'appointments.store');

//Api for show appoitments (my appointments) for patient
Route::get('/my_appointments', [AppointmentsController::class, 'show_my_appointments'])->name(name: 'appointments.show_my_appointments');

//Api for show all Subservices
Route::get('/subservices', [SubServiceController::class, 'index'])->name(name: 'subservices.index');

//Api for show all Subservices under spesific Service
Route::get('/subservices/{service}', [SubServiceController::class, 'show'])->name(name: 'subservices.show');

//Api for send selected subservices
Route::post('/selected_subservices', [SubServiceController::class, 'selectSubservices'])->name(name: 'subservices.selectSubservices');

//Api for send selected patient profile
Route::post('/selected_patient', [PatientAgentController::class, 'selectPatient'])->name(name: 'patients.selectPatient');

//Api for send selected provider
Route::post('/selected_provider', [AppointmentsController::class, 'selectProvider'])->name(name: 'appointments.selectProvider');

//Api for show day work times
Route::get('/worktimes/{date}', [HealthcareProviderWorktimeController::class, 'show_day_worktimes'])->name(name: 'careprovidersworktimes.showday');

//Api for show appoitments (accepted appointments) for patient
Route::get('/my_accepted_appointments', [AppointmentsController::class, 'show_my_accepted_appointments'])->name(name: 'appointments.show_my_accepted_appointments');

//Api for show available days in month 
Route::get('/availabel_days_in_month/{month}', [HealthcareProviderWorktimeController::class, 'availabel_days_in_month'])->name(name: 'careprovidersworktimes.availabel_days_in_month');

//Apr for show activities for care provider
Route::get('/provider_activity', [ActivitySubServiceController::class, 'Show_provider_activities'])->name(name: 'activitysubservice.provider_activities');