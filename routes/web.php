<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

use App\Models\Role;

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


Route::group(['middleware' => 'web_check_guest'], function() {
    Route::get('/', [AuthController::class, 'login'])->name('login');
});

Route::group(['middleware' => 'web_role:' . Role::SUPER_ADMIN], function() {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    Route::get('/manage_products', [ProductController::class, 'index'])->name('manage.products');
});

Route::group(['middleware' => 'web_role:' . Role::CUSTOMER], function() {
    Route::get('/order', [CustomerController::class, 'order'])->name('order');
});

Route::group(['middleware' => 'web_role:' . Role::SUPER_ADMIN. '|' . Role::CUSTOMER], function() {
    
});
