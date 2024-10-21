<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;

use App\Models\Role;

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

Route::group(['middleware' => 'guest'], function() {
    Route::post('/login_process', [AuthController::class, 'loginProcess'])->name('login_process');
});

Route::group(['middleware' => ['jwt', 'role:' . Role::SUPER_ADMIN]], function () {
    Route::get('/test', [DashboardController::class, 'test'])->name('test');
});

Route::group(['middleware' => ['jwt', 'role:' . Role::CUSTOMER]], function () {
    // Define your routes here
});

