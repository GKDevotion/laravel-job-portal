<?php

use App\Http\Controllers\Employee\ApplicationController;
use App\Http\Controllers\Employee\AuthController;
use App\Http\Controllers\Employee\DashboardController;
use App\Http\Controllers\Employee\JobListingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Employee Routes — guard: employee, prefix: /employer/team
| (Company staff invited by the employer for HR/recruitment tasks)
|--------------------------------------------------------------------------
*/

Route::prefix('employer/team')->name('employee.')->group(function () {

    Route::middleware('guest:employee')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);

        // Invitation link from the employer's "invite employee" email
        Route::get('set-password/{token}', [AuthController::class, 'showSetPassword'])->name('set-password.show');
        Route::post('set-password', [AuthController::class, 'setPassword'])->name('set-password');
    });

    Route::middleware('auth:employee')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');
        Route::get('jobs/create', [JobListingController::class, 'create'])->name('jobs.create');
        Route::post('jobs', [JobListingController::class, 'store'])->name('jobs.store');
        Route::get('jobs/{job}/edit', [JobListingController::class, 'edit'])->name('jobs.edit');
        Route::put('jobs/{job}', [JobListingController::class, 'update'])->name('jobs.update');
        Route::post('jobs/{job}/submit-review', [JobListingController::class, 'submitForReview'])->name('jobs.submit-review');

        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
