<?php

use App\Http\Controllers\SessionController;
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

// Route::get('/posts/create' ,[PostController::class ,'create']) -> name('posts.create') ;
// Route::get('/posts/{post}' , [PostController::class, 'show' ] )-> name('posts.show') ;
// //Route::post('/posts/store' ,function(){return 'hello laila';})  -> name('posts.store');
// Route::post('/posts/store' ,[PostController::class , 'store'])  -> name('posts.store');
// Route::get('/posts/{post}/edit' ,[PostController::class , 'edit']) -> name('posts.edit');
// Route::put('posts/{post}',[PostController::class , 'update'])-> name('posts.update');
// //Route::delete('posts/{post}', function(){return 'we in page';}) -> name('posts.destroy');
//  Route::delete('posts/{post}', [PostController::class , 'destroy']) -> name('posts.destroy');
// // 1. create Route , testing
// // 2. create controller file , function
// // 3. create view
// // 4. remove to static code

Route::get('/', function () {
    return view('welcome');
});
//create route to "index sessions":
Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');

//create route to "create session":


//create route to "store session":
Route::post('/sessions', [SessionController::class, 'store'])->name('sessions.store');
//Route::post('/sessions' , function() {return 'hello world';})  -> name('sessions.store');

//create route to "show session"
Route::get('/sessions/{session}', [SessionController::class, 'show'])->name('sessions.show');

//create route to "edit(show) session":
Route::get('sessions/{session}/edit', [SessionController::class, 'edit'])->name('sessions.edit');

//update route to "update session":
Route::put('sessions/{session}' , function(){return 'hello';})->name('sessions.update');
