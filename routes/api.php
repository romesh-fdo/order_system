<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\OrderController;

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
    Route::post('/products', [ProductController::class, 'store'])->name('products.store');
    Route::post('/products/update/{id}', [ProductController::class, 'update'])->name('products.update');
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::delete('/products/{id}', [ProductController::class, 'destroy'])->name('products.destroy');
    Route::get('/products', [ProductController::class, 'getProductsData'])->name('products.all');
    Route::post('/products/show', [ProductController::class, 'show'])->name('products.show');

    Route::get('/orders', [OrderController::class, 'getOrderData'])->name('orders.all');
    Route::post('/orders/show', [OrderController::class, 'show'])->name('orders.show');
    Route::post('/orders/cancel', [OrderController::class, 'cancelOrder'])->name('orders.cancel');
    Route::post('/orders/complete', [OrderController::class, 'completeOrder'])->name('orders.complete');
});

Route::group(['middleware' => ['jwt', 'role:' . Role::CUSTOMER]], function () {
    Route::post('/orders/place', [OrderController::class, 'place'])->name('orders.place');
    Route::get('/orders/my_orders', [OrderController::class, 'getMyOrders'])->name('orders.my_orders');
});

Route::group(['middleware' => ['jwt', 'role:' . Role::SUPER_ADMIN. '|' . Role::CUSTOMER]], function() {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
});
