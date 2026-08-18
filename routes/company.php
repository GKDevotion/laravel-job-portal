<?php

use App\Http\Controllers\Company\ApplicationController;
use App\Http\Controllers\Company\AuthController;
use App\Http\Controllers\Company\DashboardController;
use App\Http\Controllers\Company\EmployeeController;
use App\Http\Controllers\Company\JobListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Company (Employer) Routes — guard: company, prefix: /employer
|--------------------------------------------------------------------------
*/

Route::prefix('employer')->name('company.')->group(function () {

    Route::middleware('guest:company')->group(function () {
        Route::get('register', [AuthController::class, 'showRegister'])->name('register');
        Route::post('register', [AuthController::class, 'register']);
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:company')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');
        Route::get('jobs/create', [JobListingController::class, 'create'])->name('jobs.create');
        Route::post('jobs', [JobListingController::class, 'store'])->name('jobs.store');
        Route::get('jobs/{job}/edit', [JobListingController::class, 'edit'])->name('jobs.edit');
        Route::put('jobs/{job}', [JobListingController::class, 'update'])->name('jobs.update');
        Route::post('jobs/{job}/submit-review', [JobListingController::class, 'submitForReview'])->name('jobs.submit-review');
        Route::post('jobs/{job}/close', [JobListingController::class, 'close'])->name('jobs.close');
        Route::delete('jobs/{job}', [JobListingController::class, 'destroy'])->name('jobs.destroy');

        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');

        // Team/employee management — the employer account itself always has "manage team" rights
        Route::get('team', [EmployeeController::class, 'index'])->name('employees.index');
        Route::get('team/invite', [EmployeeController::class, 'create'])->name('employees.create');
        Route::post('team', [EmployeeController::class, 'store'])->name('employees.store');
        Route::patch('team/{employee}/toggle-status', [EmployeeController::class, 'toggleStatus'])->name('employees.toggle-status');
        Route::delete('team/{employee}', [EmployeeController::class, 'destroy'])->name('employees.destroy');

        // Route::get('invoices', [Company\InvoiceController::class, 'index'])->name('invoices.index'); // Phase 5

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
