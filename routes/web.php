<?php

use App\Http\Controllers\SessionController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\HealthcareProviderController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});
Route::post('//sessions', [SessionController::class, 'store'])->name('<sections.store');

// routes to the ServiceController
Route::get('/services', [ServiceController::class, 'index']) -> name(name: 'services.index');
Route::get('/services/creat', [ServiceController::class, 'creat']) -> name(name:'services.creat');
Route::post('/services', [ServiceController::class, 'store']) -> name(name:'services.store');
Route::get('/services/{service}', [ServiceController::class, 'show']) -> name(name:'services.show');
Route::get('/services/{service}/edit', [ServiceController::class, 'edit']) -> name(name:'services.edit');
Route::put('/services/{service}', [ServiceController::class, 'update']) -> name(name:'services.update');
Route::delete('/services/{service}', [ServiceController::class, 'destroy']) -> name(name:'services.destroy');

//routes to the HealthcareProviderController
Route::get('/providers', [HealthcareProviderController::class, 'index']) -> name(name: 'providers.index');
Route::get('/providers/creat', [HealthcareProviderController::class, 'creat']) -> name(name:'providers.creat');
Route::post('/providers', [HealthcareProviderController::class, 'store']) -> name(name:'providers.store');
Route::get('/providers/{provider}', [HealthcareProviderController::class, 'show']) -> name(name:'providers.show');
Route::get('/providers/{provider}/edit', [HealthcareProviderController::class, 'edit']) -> name(name:'providers.edit');
Route::put('/providers/{provider}', [HealthcareProviderController::class, 'update']) -> name(name:'providers.update');
Route::delete('/providers/{provider}', [HealthcareProviderController::class, 'destroy']) -> name(name:'providers.destroy');