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

//create route to "index sessions":
Route::get('/sessions', [ApiSessionController::class, 'index'])->name('sessions.index');
//create route to "create session":
Route::get('/sessions/create', [ApiSessionController::class, 'create'])->name('sessions.create');
//create route to "show session"
Route::get('/sessions/{session}', [ApiSessionController::class, 'show'])->name('sessions.show');
//create route to "edit(show) session":
Route::get('sessions/{session}/edit', [ApiSessionController::class, 'edit'])->name('sessions.edit');
//update route to "update session":
Route::put('sessions/{session}', [ApiSessionController::class, 'update'])->name('sessions.update');
//مسار ملخص لوحة المتابعة
Route::get('sessions/summary/{session}', [ApiSessionController::class, 'session_summary'])->name('sessions.summary');
//"store session"
Route::post('/sessions', [ApiSessionController::class, 'store'])->name('sessions.store');

// Api's for search page
Route::get('/search', [SearchController::class, 'index'])->name(name: 'search.index');

//Api for the result of search (care providers)
//Route::get('/providers', [HealthcareProviderController::class, 'index']) -> name(name: 'providers.index');
