<?php

use App\Http\Controllers\Front\CompanyController;
use App\Http\Controllers\Front\HomeController;
use App\Http\Controllers\Front\JobListingController;
use App\Http\Controllers\Seeker\ApplicationController;
use App\Http\Controllers\Seeker\AuthController;
use App\Http\Controllers\Seeker\DashboardController;
use App\Http\Controllers\Seeker\SavedJobController;
use App\Http\Controllers\SitemapController;
use Illuminate\Support\Facades\Route;
use Mcamara\LaravelLocalization\Facades\LaravelLocalization;

// Not locale-prefixed — a sitemap lists locale-specific URLs internally (see SitemapController)
// but the sitemap.xml URL itself should stay stable for search engines to crawl.
Route::get('sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Admin panel is intentionally OUTSIDE the locale-prefixed group below: it's an internal
// management tool, not public/SEO-facing content, so URLs stay plain "/admin/..." rather than
// "/en/admin/...". This matches config/laravellocalization.php's urlsIgnored setting — keep
// both in sync if that ever changes. The admin panel still respects the app locale for its own
// UI (session/browser-driven), it just doesn't need it baked into the URL.
require __DIR__.'/admin.php';

/*
|--------------------------------------------------------------------------
| Everything below IS locale-prefixed: /en/jobs, /ar/jobs, /fr/jobs, /hi/jobs.
| hideDefaultLocaleInURL is false (see config/laravellocalization.php) so
| even English always shows /en/... explicitly — no ambiguous bare "/".
|--------------------------------------------------------------------------
*/

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => ['localize', 'localizationRedirect', 'localeSessionRedirect', 'localeViewPath'],
    ],
    function () {

        // --- Public browsing — no auth required ---
        Route::get('/', [HomeController::class, 'index'])->name('home');
        Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');
        Route::get('jobs/{jobListing:slug}', [JobListingController::class, 'show'])->name('jobs.show');
        Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
        Route::get('companies/{company:slug}', [CompanyController::class, 'show'])->name('companies.show');

        // --- Job seeker (guard: web) ---
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

            Route::get('profile', [\App\Http\Controllers\Seeker\ProfileController::class, 'edit'])->name('profile.edit');
            Route::put('profile', [\App\Http\Controllers\Seeker\ProfileController::class, 'update'])->name('profile.update');
            Route::post('profile/avatar', [\App\Http\Controllers\Seeker\ProfileController::class, 'updateAvatar'])->name('profile.avatar');
            Route::post('profile/resume', [\App\Http\Controllers\Seeker\ProfileController::class, 'updateResume'])->name('profile.resume');

            Route::post('logout', [AuthController::class, 'logout'])->name('logout');
        });

        require __DIR__.'/company.php';
        require __DIR__.'/employee.php';
    }
);
