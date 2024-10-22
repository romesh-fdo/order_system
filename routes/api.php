<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;

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
    Route::post('/api/products', [ProductController::class, 'store'])->name('products.store');
    Route::put('/api/products/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/api/products', [ProductController::class, 'index'])->name('products.index');
    Route::delete('/api/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/api/products/all', [ProductController::class, 'getProductsData'])->name('products.all');
    Route::post('/api/products/show', [ProductController::class, 'show'])->name('products.show');
});

Route::group(['middleware' => ['jwt', 'role:' . Role::CUSTOMER]], function () {
    // Define your routes here
});

