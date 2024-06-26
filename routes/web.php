<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HealthcareProviderController;
use App\Http\Controllers\SearchController;
use Illuminate\Support\Facades\Route;


//create route to "index sessions":
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
Route::get('/sessions/patientsessoins/{patient}', [SessionController::class, 'patientSessions'])->name('sessions.sessions');
//create route to "create session":
Route::get('/sessions/create/{appintments}', [SessionController::class, 'create'])->name('sessions.create');
//create route to "store session":
Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
//create route to "show session"
Route::get('/sessions/{session}', [SessionController::class, 'show'])->name('sessions.show');
//create route to "edit(show) session":
Route::get('sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');
//update route to "update session":
Route::put('sessions/{session}', function () {
    return 'hello';
})->name('sessions.update');
Route::get('/sessions/summary/{session}', [SessionController::class, 'session_summary'])->name('sessions.summary');











// // routes to the ServiceController
// Route::get('/services', [ServiceController::class, 'index'])->name(name: 'services.index');
// Route::get('/services/create', [ServiceController::class, 'create'])->name(name: 'services.create');
// Route::post('/services', [ServiceController::class, 'store'])->name(name: 'services.store');
// Route::get('/services/{service}', [ServiceController::class, 'show'])->name(name: 'services.show');
// Route::get('/services/{service}/edit', [ServiceController::class, 'edit'])->name(name: 'services.edit');
// Route::put('/services/{service}', [ServiceController::class, 'update'])->name(name: 'services.update');
// Route::delete('/services/{service}', [ServiceController::class, 'destroy'])->name(name: 'services.destroy');

// //routes to the HealthcareProviderController
// Route::get('/providers', [HealthcareProviderController::class, 'index']) -> name(name: 'providers.index');
// Route::get('/providers/create', [HealthcareProviderController::class, 'create']) -> name(name:'providers.create');
// Route::post('/providers', [HealthcareProviderController::class, 'store']) -> name(name:'providers.store');
// Route::get('/providers/{provider}', [HealthcareProviderController::class, 'show']) -> name(name:'providers.show');
// Route::get('/providers/{provider}/edit', [HealthcareProviderController::class, 'edit']) -> name(name:'providers.edit');
// Route::put('/providers/{provider}', [HealthcareProviderController::class, 'update']) -> name(name:'providers.update');
// Route::delete('/providers/{provider}', [HealthcareProviderController::class, 'destroy']) -> name(name:'providers.destroy');

// //route to search page
// Route::get('/search', [SearchController::class, 'index']) -> name(name: 'search.index');
