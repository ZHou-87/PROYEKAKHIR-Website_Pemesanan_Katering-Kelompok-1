<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/customer/dashboard', [DashboardController::class, 'customer'])
    ->middleware(['auth', 'role:customer'])
    ->name('customer.dashboard');

Route::get('/admin/dashboard', [DashboardController::class, 'admin'])
    ->middleware(['auth', 'role:admin'])
    ->name('admin.dashboard');

Route::get('/owner/dashboard', [DashboardController::class, 'owner'])
    ->middleware(['auth', 'role:owner'])
    ->name('owner.dashboard');

Route::middleware(['auth', 'role:owner', 'can:lihat-laporan'])->group(function () {
    Route::get('/owner/reports', [ReportController::class, 'index'])->name('owner.reports.index');
    Route::get('/owner/reports/print', [ReportController::class, 'print'])->name('owner.reports.print');
});
