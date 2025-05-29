<?php

use App\Http\Controllers\FaqFileController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Laravel\WorkOS\Http\Middleware\ValidateSessionWithWorkOS;

Route::get('/', fn () => Inertia::render('Welcome'));

Route::middleware([
    'auth',
    ValidateSessionWithWorkOS::class,
])->group(function () {
    Route::get('dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::get('home', function () {
        return Inertia::render('Home');
    })->name('home');

    // FAQ File Management API routes
    Route::apiResource('api/faq-files', FaqFileController::class);
});

require __DIR__.'/settings.php';
require __DIR__.'/auth.php';
