<?php

use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JobController;
use Illuminate\Support\Facades\Route;

Route::get('/', HomeController::class)->name('home');
Route::get('/jobs', [JobController::class, 'index'])->name('jobs.index');
Route::get('/jobs/{slug}', [JobController::class, 'show'])->name('jobs.show');

// Temporary entry points. Full authentication and role workflows are implemented in a later batch.
Route::view('/login', 'auth.placeholder', ['heading' => 'Sign in'])->name('login');
Route::view('/register', 'auth.placeholder', ['heading' => 'Create your candidate account'])->name('register');
Route::view('/employer/register', 'auth.placeholder', ['heading' => 'Create your employer account'])->name('employer.register');

Route::fallback(fn () => response()->view('errors.404', [], 404));
