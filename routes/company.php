<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Company (Employer) Routes — guard: company, prefix: /employer
|--------------------------------------------------------------------------
*/

Route::prefix('employer')->name('company.')->group(function () {

    Route::middleware('guest:company')->group(function () {
        // Route::get('register', [Company\AuthController::class, 'showRegister'])->name('register');
        // Route::post('register', [Company\AuthController::class, 'register']);
        // Route::get('login', [Company\AuthController::class, 'showLogin'])->name('login');
        // Route::post('login', [Company\AuthController::class, 'login']);
    });

    Route::middleware('auth:company')->group(function () {
        // Route::get('dashboard', [Company\DashboardController::class, 'index'])->name('dashboard');
        // Route::resource('jobs', Company\JobListingController::class);
        // Route::get('applications', [Company\ApplicationController::class, 'index'])->name('applications.index');

        // Team/employee management — only the company owner or employees with permission
        // Route::middleware('permission:manage team,company')->group(function () {
        //     Route::resource('employees', Company\EmployeeController::class);
        // });

        // Route::get('invoices', [Company\InvoiceController::class, 'index'])->name('invoices.index');
        // Route::post('logout', [Company\AuthController::class, 'logout'])->name('logout');
    });
});
