<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\InvoiceController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Admin Routes — guard: admin, prefix: /admin
|--------------------------------------------------------------------------
| Only auth + dashboard + invoice review are wired so far (needed to exercise
| Phase 5's invoicing flow). Company approval, user management, settings,
| languages/currencies management, and the translation tool are Phase 6.
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

        // Route::get('companies', [Admin\CompanyController::class, 'index'])->name('companies.index'); // Phase 6
        // Route::get('users', [Admin\UserController::class, 'index'])->name('users.index');            // Phase 6
        // Route::get('settings/languages', ...)->name('settings.languages');                            // Phase 6
        // Route::get('settings/currencies', ...)->name('settings.currencies');                          // Phase 6

        Route::post('logout', [AuthController::class, 'logout'])->name('logout');
    });
});
