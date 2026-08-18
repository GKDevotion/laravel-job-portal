<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public + Job Seeker Routes — guard: web (default)
|--------------------------------------------------------------------------
*/

// Public job browsing — no auth required
// Route::get('/', [Front\HomeController::class, 'index'])->name('home');
// Route::get('jobs', [Front\JobListingController::class, 'index'])->name('jobs.index');
// Route::get('jobs/{jobListing:slug}', [Front\JobListingController::class, 'show'])->name('jobs.show');
// Route::get('companies', [Front\CompanyController::class, 'index'])->name('companies.index');
// Route::get('companies/{company:slug}', [Front\CompanyController::class, 'show'])->name('companies.show');

Route::middleware('guest:web')->group(function () {
    // Route::get('register', [Seeker\AuthController::class, 'showRegister'])->name('register');
    // Route::post('register', [Seeker\AuthController::class, 'register']);
    // Route::get('login', [Seeker\AuthController::class, 'showLogin'])->name('login');
    // Route::post('login', [Seeker\AuthController::class, 'login']);
});

Route::middleware('auth:web')->group(function () {
    // Route::get('dashboard', [Seeker\DashboardController::class, 'index'])->name('dashboard');
    // Route::post('jobs/{jobListing}/apply', [Seeker\ApplicationController::class, 'store'])->name('jobs.apply');
    // Route::post('jobs/{jobListing}/save', [Seeker\SavedJobController::class, 'toggle'])->name('jobs.save');
    // Route::post('logout', [Seeker\AuthController::class, 'logout'])->name('logout');
});

// require __DIR__.'/admin.php';
// require __DIR__.'/company.php';
// require __DIR__.'/employee.php';
