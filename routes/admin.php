<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — guard: admin, prefix: /admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    // Guest-only (login)
    Route::middleware('guest:admin')->group(function () {
        // Route::get('login', [Admin\AuthController::class, 'showLogin'])->name('login');
        // Route::post('login', [Admin\AuthController::class, 'login']);
    });

    // Authenticated admin area
    Route::middleware('auth:admin')->group(function () {
        // Route::get('dashboard', [Admin\DashboardController::class, 'index'])->name('dashboard');

        // Example: only admins with "manage companies" permission can approve employers
        // Route::middleware('permission:manage companies,admin')->group(function () {
        //     Route::get('companies', [Admin\CompanyController::class, 'index'])->name('companies.index');
        //     Route::post('companies/{company}/approve', [Admin\CompanyController::class, 'approve'])->name('companies.approve');
        // });

        // Route::post('logout', [Admin\AuthController::class, 'logout'])->name('logout');
    });
});
