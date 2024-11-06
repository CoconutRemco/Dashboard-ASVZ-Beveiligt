<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Log;

// Welcome page route
Route::get('/', function () {
    Log::info('User accessed the welcome page');
    return view('welcome');
});

// Dashboard route (protected by Sanctum authentication)
Route::middleware('auth')->get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard')->middleware(function ($request, $next) {
    Log::info('User accessed the dashboard');
    return $next($request);
});

// Authentication routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login')->middleware(function ($request, $next) {
        Log::info('User attempted login');
        return $next($request);
    });

    // Register routes
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register')->middleware(function ($request, $next) {
        Log::info('User attempted registration');
        return $next($request);
    });

    // Forgot password routes
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request')->middleware(function ($request, $next) {
        Log::info('User requested password reset link');
        return $next($request);
    });
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email')->middleware(function ($request, $next) {
        Log::info('User submitted password reset request');
        return $next($request);
    });
});

// Logout route (protected by Sanctum authentication)
Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout')->middleware(function ($request, $next) {
    Log::info('User logged out');
    return $next($request);
});

// Profile routes (protected by Sanctum authentication)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit')->middleware(function ($request, $next) {
        Log::info('User accessed profile edit page');
        return $next($request);
    });
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update')->middleware(function ($request, $next) {
        Log::info('User updated profile');
        return $next($request);
    });
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy')->middleware(function ($request, $next) {
        Log::info('User deleted profile');
        return $next($request);
    });
});

// MQTT routes (protected by Sanctum authentication)
Route::middleware('auth')->group(function () {
    // Send message via MQTT (using DashboardController)
    Route::post('/send-message', [DashboardController::class, 'sendMessage'])->name('send.message')->middleware(function ($request, $next) {
        Log::info('User sent an MQTT message');
        return $next($request);
    });
});

Route::middleware('throttle:60,1')->group(function () {
    Route::get('/user', function () {
        return 'Rate limited';
    });
});

// Include authentication routes (Sanctum login/register)
require __DIR__.'/auth.php';
