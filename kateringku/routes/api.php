<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\MenuController as ApiMenuController;
use App\Http\Controllers\Api\V1\OrderController as ApiOrderController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1')->name('v1.')->group(function () {
    Route::post('/login', [AuthController::class, 'login'])
        ->middleware('throttle:5,1')
        ->name('login');

    Route::apiResource('menus', ApiMenuController::class)->only(['index', 'show']);

    Route::middleware('auth:sanctum')->group(function () {
        Route::get('/me', [AuthController::class, 'me'])->name('me');
        Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

        Route::apiResource('menus', ApiMenuController::class)->only(['store', 'update', 'destroy']);

        Route::apiResource('orders', ApiOrderController::class)->only(['index', 'store', 'show']);
        Route::post('/orders/{order}/pay', [ApiOrderController::class, 'pay'])->name('orders.pay');
        Route::post('/orders/{order}/cancel', [ApiOrderController::class, 'cancel'])->name('orders.cancel');
        Route::patch('/orders/{order}/status', [ApiOrderController::class, 'updateStatus'])
            ->middleware('can:validasi-pesanan')
            ->name('orders.update-status');
    });
});
