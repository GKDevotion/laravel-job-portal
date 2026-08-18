<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use App\Http\Controllers\Admin\JobListingController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\TranslationController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — guard: admin, prefix: /admin
|--------------------------------------------------------------------------
*/

Route::prefix('admin')->name('admin.')->group(function () {

    Route::middleware('guest:admin')->group(function () {
        Route::get('login', [AuthController::class, 'showLogin'])->name('login');
        Route::post('login', [AuthController::class, 'login']);
    });

    Route::middleware('auth:admin')->group(function () {
        Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

        Route::middleware('permission:manage invoices,admin')->group(function () {
            Route::get('invoices', [InvoiceController::class, 'index'])->name('invoices.index');
            Route::get('invoices/{invoice}', [InvoiceController::class, 'show'])->name('invoices.show');
            Route::post('invoices/{invoice}/mark-paid', [InvoiceController::class, 'markPaid'])->name('invoices.mark-paid');
            Route::post('invoices/{invoice}/cancel', [InvoiceController::class, 'cancel'])->name('invoices.cancel');
        });

        Route::middleware('permission:approve companies,admin')->group(function () {
            Route::get('companies', [CompanyController::class, 'index'])->name('companies.index');
            Route::get('companies/{company}', [CompanyController::class, 'show'])->name('companies.show');
            Route::post('companies/{company}/approve', [CompanyController::class, 'approve'])->name('companies.approve');
            Route::post('companies/{company}/reject', [CompanyController::class, 'reject'])->name('companies.reject');
            Route::patch('companies/{company}/toggle-status', [CompanyController::class, 'toggleStatus'])->name('companies.toggle-status');
        });

        Route::middleware('permission:manage jobs,admin')->group(function () {
            Route::get('jobs', [JobListingController::class, 'index'])->name('jobs.index');
            Route::get('jobs/{job}', [JobListingController::class, 'show'])->name('jobs.show');
            Route::post('jobs/{job}/approve', [JobListingController::class, 'approve'])->name('jobs.approve');
            Route::post('jobs/{job}/reject', [JobListingController::class, 'reject'])->name('jobs.reject');
        });

        Route::middleware('permission:manage users,admin')->group(function () {
            Route::get('users', [UserController::class, 'index'])->name('users.index');
            Route::patch('users/{user}/toggle-status', [UserController::class, 'toggleStatus'])->name('users.toggle-status');
        });

        Route::middleware('permission:manage languages,admin')->group(function () {
            Route::get('settings/languages', [LanguageController::class, 'index'])->name('settings.languages');
            Route::post('settings/languages', [LanguageController::class, 'store'])->name('settings.languages.store');
            Route::patch('settings/languages/{language}/toggle-status', [LanguageController::class, 'toggleStatus'])->name('settings.languages.toggle-status');
            Route::post('settings/languages/{language}/set-default', [LanguageController::class, 'setDefault'])->name('settings.languages.set-default');
            Route::delete('settings/languages/{language}', [LanguageController::class, 'destroy'])->name('settings.languages.destroy');

            Route::get('settings/translations', [TranslationController::class, 'edit'])->name('settings.translations');
            Route::post('settings/translations', [TranslationController::class, 'update'])->name('settings.translations.update');
        });

        Route::middleware('permission:manage currencies,admin')->group(function () {
            Route::get('settings/currencies', [CurrencyController::class, 'index'])->name('settings.currencies');
            Route::post('settings/currencies', [CurrencyController::class, 'store'])->name('settings.currencies.store');
            Route::put('settings/currencies/{currency}', [CurrencyController::class, 'update'])->name('settings.currencies.update');
            Route::post('settings/currencies/{currency}/set-default', [CurrencyController::class, 'setDefault'])->name('settings.currencies.set-default');
            Route::patch('settings/currencies/{currency}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('settings.currencies.toggle-status');
        });

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
