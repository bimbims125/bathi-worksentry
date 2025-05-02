<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\User\ClockInController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('/auth')->group(function (){
    Route::controller(AuthController::class)->group(function (){
        Route::get('/login', 'index')->name('auth.login');
    });
});

Route::prefix('/dashboard')->group(function (){
    Route::controller(DashboardController::class)->group(function (){
        Route::get('', 'index')->name('dashboard.index');
    });
});

Route::prefix('/user')->group(function (){
    Route::controller(ClockInController::class)->group(function (){
        Route::get('/clock-in', 'index');
    });
});
