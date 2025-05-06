<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\GeofenceController;
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

// Auth Routes
Route::middleware(['web'])->prefix('auth/')->controller(AuthController::class)->group(function (){
    Route::get('login', 'index')->name('auth.login')->middleware('guest');
    Route::post('authenticate', 'authenticate')->name('auth.authenticate');
    // Route::get('logout', 'logout')->name('logout');
});
// End auth routes


Route::prefix('/admin')->group(function (){
    Route::controller(DashboardController::class)->group(function (){
        Route::get('/dashboard', 'index')->name('admin.dashboard.index');
    });

    Route::controller(GeofenceController::class)->group(function (){
        Route::get('/geofence', 'index')->name('admin.geofence.index');
        Route::post('/geofence', 'store')->name('admin.geofence.store');
    });
});

Route::prefix('/user')->group(function (){
    Route::controller(ClockInController::class)->group(function (){
        Route::get('/clock-in', 'index')->name('user.clock-in.index');
        Route::post('/clock-in', 'store')->name('user.clock-in.store');
    });
});
