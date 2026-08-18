<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Employee Routes — guard: employee, prefix: /employer/team
| (Company staff invited by the employer for HR/recruitment tasks)
|--------------------------------------------------------------------------
*/

Route::prefix('employer/team')->name('employee.')->group(function () {

    Route::middleware('guest:employee')->group(function () {
        // Route::get('login', [Employee\AuthController::class, 'showLogin'])->name('login');
        // Route::post('login', [Employee\AuthController::class, 'login']);
        // Route::get('invitation/{token}', [Employee\InvitationController::class, 'accept'])->name('invitation.accept');
    });

    Route::middleware('auth:employee')->group(function () {
        // Route::get('dashboard', [Employee\DashboardController::class, 'index'])->name('dashboard');

        // Employees only see/manage data scoped to their own company — enforced in controllers/policies
        // Route::middleware('permission:review applications,employee')->group(function () {
        //     Route::get('applications', [Employee\ApplicationController::class, 'index'])->name('applications.index');
        // });

        // Route::post('logout', [Employee\AuthController::class, 'logout'])->name('logout');
    });
});
