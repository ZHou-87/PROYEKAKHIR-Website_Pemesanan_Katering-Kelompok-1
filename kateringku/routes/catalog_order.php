<?php

use App\Http\Controllers\CatalogController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\OrderController;
use Illuminate\Support\Facades\Route;

// Katalog Publik (Guest & Authenticated)
Route::get('/catalog', [CatalogController::class, 'index'])->name('catalog.index');
Route::get('/catalog/{menu}/order', [CatalogController::class, 'order'])
    ->middleware('auth')
    ->name('catalog.order');

// Kelola Menu (Owner & Admin)
Route::middleware(['auth', 'can:kelola-menu'])->group(function () {
    Route::get('/menus/api-client', [MenuController::class, 'apiClient'])->name('menus.api-client');
    Route::resource('menus', MenuController::class)->except(['show']);
});

// Transaksi Pesanan Customer (Authenticated)
Route::middleware(['auth'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/create', [OrderController::class, 'create'])->name('orders.create');
    Route::post('/orders', [OrderController::class, 'store'])->name('orders.store');
    Route::get('/orders/{order}/pay', [OrderController::class, 'pay'])->name('orders.pay');
    Route::post('/orders/{order}/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
    Route::post('/orders/{order}/cancel', [OrderController::class, 'cancel'])->name('orders.cancel');
});

// Validasi Pesanan (Admin & Owner)
Route::middleware(['auth', 'role:admin|owner', 'can:validasi-pesanan'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {
        Route::get('/orders', [OrderController::class, 'adminIndex'])->name('orders.index');
        Route::patch('/orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.update-status');
    });
