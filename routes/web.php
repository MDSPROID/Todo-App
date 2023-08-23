<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AppController;

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

// CLEAR CACHE
Route::get('/clear-cache', function() {
	$date_start = date('Y-m-d H:i:s');
	$exitCode1 = Artisan::call('cache:clear');
    $exitCode2 = Artisan::call('optimize:clear'); 
    $exitCode4 = Artisan::call('route:clear'); 
    $exitCode5 = Artisan::call('view:clear');
    $exitCode6 = Artisan::call('config:cache');
    return "Cache Clear : ".$exitCode1."<br>"."Optimize Clear : ".$exitCode2."<br>"."Routed Clear : ".$exitCode4."<br>"."View Cache Clear : ".$exitCode5."<br>"."Config Cache Clear : ".$exitCode6."<br>"; 
});
// END CLEAR CACHE

Route::middleware(['xss'])->group(function () {
    Route::get('/',[AppController::class,'index']);
    Route::get('/auth/in',[AppController::class,'auth']);
    Route::post('login', [AppController::class, 'login']);
    Route::get('logout', [AppController::class, 'logout']);
    Route::post('/register',[AppController::class,'register']);

    Route::middleware(['cekLogin'])->group(function () {
        Route::prefix('apps')->group(function () {
            Route::get('/todo',[AppController::class,'todo']);
            Route::get('/todo/getdata',[AppController::class,'getdata']);
            Route::post('/todo/save',[AppController::class,'save']);
            Route::get('/todo/delete/{id}',[AppController::class,'delete']);
        });       
    });        
});
