<?php

use App\Http\Controllers\Front\CompanyController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\JobListingController;
use App\Http\Controllers\Seeker\ApplicationController;
use App\Http\Controllers\Seeker\AuthController;
use App\Http\Controllers\Seeker\DashboardController;
use App\Http\Controllers\Seeker\SavedJobController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public + Job Seeker Routes — guard: web (default)
|--------------------------------------------------------------------------
*/

// Public browsing — no auth required
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');
Route::get('jobs/{jobListing:slug}', [JobListingController::class, 'show'])->name('jobs.show');
Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
Route::get('companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

Route::middleware('guest:web')->group(function () {
    Route::get('register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('register', [AuthController::class, 'register']);
    Route::get('login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('login', [AuthController::class, 'login']);
});

Route::middleware('auth:web')->group(function () {
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::post('jobs/{jobListing}/apply', [ApplicationController::class, 'store'])->name('jobs.apply');
    Route::post('jobs/{jobListing}/save', [SavedJobController::class, 'toggle'])->name('jobs.save');
    Route::get('saved-jobs', [SavedJobController::class, 'index'])->name('saved-jobs.index');

    Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::delete('applications/{application}', [ApplicationController::class, 'withdraw'])->name('applications.withdraw');

    Route::post('logout', [AuthController::class, 'logout'])->name('logout');
});

require __DIR__.'/admin.php';
require __DIR__.'/company.php';
require __DIR__.'/employee.php';
