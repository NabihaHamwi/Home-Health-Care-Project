<?php

use App\Http\Controllers\Api\ApiSessionController as ApiSessionController;
use App\Http\Controllers\Api\SearchController;
use App\Http\Controllers\Api\ServiceController;
use App\Http\Controllers\Api\SkillController;
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
Route::group([] ,
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

// Route to search page
 Route::get('/search', [SearchController::class, 'index'])->name('search.index');

// Api for the result of search (care providers)
// Route::get('/providers', [HealthcareProviderController::class, 'index']) -> name('providers.index');
