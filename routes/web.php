<?php

use App\Http\Controllers\Admin\JobCategoryController;
use App\Http\Controllers\Admin\JobReviewController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\NewPasswordController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Employer\JobManagementController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use App\Http\Controllers\ProfileController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthenticatedSessionController::class, 'create'])->name('login');
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->middleware('throttle:10,1');
    Route::get('/register', [RegisteredUserController::class, 'createCandidate'])->name('register');
    Route::post('/register', fn (Request $request) => app(RegisteredUserController::class)->store($request, 'candidate'))->name('register.store');
    Route::get('/employer/register', [RegisteredUserController::class, 'createEmployer'])->name('employer.register');
    Route::post('/employer/register', fn (Request $request) => app(RegisteredUserController::class)->store($request, 'employer'))->name('employer.register.store');
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
    Route::get('/reset-password/{token}', [NewPasswordController::class, 'create'])->name('password.reset');
    Route::post('/reset-password', [NewPasswordController::class, 'store'])->name('password.store');
});

Route::middleware('auth')->group(function () {
    Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');
    Route::get('/email/verify', fn () => view('auth.verify-email'))->name('verification.notice');
    Route::get('/email/verify/{id}/{hash}', function (EmailVerificationRequest $request) {
        $request->fulfill();

        return redirect()->route('dashboard')->with('status', 'Email verified successfully.');
    })->middleware(['signed', 'throttle:6,1'])->name('verification.verify');
    Route::post('/email/verification-notification', function (Request $request) {
        $request->user()->sendEmailVerificationNotification();

        return back()->with('status', 'Verification link sent.');
    })->middleware('throttle:6,1')->name('verification.send');
    Route::middleware('verified')->group(function () {
        Route::get('/dashboard', DashboardController::class)->name('dashboard');
        Route::middleware('role:super_admin,admin')->prefix('admin')->name('admin.')->group(function () {
            Route::view('/dashboard', 'dashboard.admin')->name('dashboard');
        });
        Route::middleware('role:candidate')->prefix('candidate')->name('candidate.')->group(function () {
            Route::get('/profile', [ProfileController::class, 'editCandidate'])->name('profile.edit');
            Route::put('/profile', [ProfileController::class, 'updateCandidate'])->name('profile.update');
        });
        Route::middleware('role:employer')->prefix('employer')->name('employer.')->group(function () {
            Route::get('/profile', [ProfileController::class, 'editEmployer'])->name('profile.edit');
            Route::put('/profile', [ProfileController::class, 'updateEmployer'])->name('profile.update');
        });
    });
});

// JobStock Batch 05-07: taxonomy, employer job management and moderation.
Route::middleware(['auth', 'verified', 'role:employer'])->prefix('employer')->name('employer.')->group(function () {
    Route::resource('jobs', JobManagementController::class)->except(['show']);
    Route::get('/applicants',
        [\App\Http\Controllers\Employer\ApplicantController::class, 'index']
    )->name('applications.index');

    Route::patch('/applicants/{application}',
        [\App\Http\Controllers\Employer\ApplicantController::class, 'update']
    )->name('applications.update');
});

Route::middleware(['auth', 'verified', 'role:super_admin,admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/categories', [JobCategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [JobCategoryController::class, 'store'])->name('categories.store');
    Route::put('/categories/{category}', [JobCategoryController::class, 'update'])->name('categories.update');
    Route::delete('/categories/{category}', [JobCategoryController::class, 'destroy'])->name('categories.destroy');
    Route::get('/job-reviews', [JobReviewController::class, 'index'])->name('jobs.review.index');
    Route::patch('/job-reviews/{job}', [JobReviewController::class, 'update'])->name('jobs.review.update');
});


// JobStock Batch 08–10: Candidate Applications, Saved Jobs and Employer Applicants

Route::middleware(['auth', 'verified', 'role:candidate'])->prefix('candidate')->name('candidate.')->group(function () {

        Route::get('/applications',
            [\App\Http\Controllers\Candidate\JobApplicationController::class, 'index']
        )->name('applications.index');

        Route::post('/jobs/{job}/apply',
            [\App\Http\Controllers\Candidate\JobApplicationController::class, 'store']
        )->name('jobs.apply');

        Route::patch('/applications/{application}/withdraw',
            [\App\Http\Controllers\Candidate\JobApplicationController::class, 'withdraw']
        )->name('applications.withdraw');

        Route::get('/saved-jobs',
            [\App\Http\Controllers\Candidate\SavedJobController::class, 'index']
        )->name('saved-jobs.index');

        Route::post('/saved-jobs/{job}',
            [\App\Http\Controllers\Candidate\SavedJobController::class, 'toggle']
        )->name('saved-jobs.toggle');

        Route::delete('/saved-jobs/{job}',
            [\App\Http\Controllers\Candidate\SavedJobController::class, 'toggle']
        )->name('saved-jobs.remove');
    });


Route::fallback(fn () => response()->view('errors.404', [], 404));
