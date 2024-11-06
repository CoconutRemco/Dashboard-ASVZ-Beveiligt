<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\Auth\PasswordResetLinkController;
use App\Http\Controllers\DashboardController;  // Update the import statement
use Illuminate\Support\Facades\Route;

// Welcome page route
Route::get('/', function () {
    return view('welcome');
});

// Dashboard route (protected by Sanctum authentication)
Route::middleware('auth:sanctum')->get('/dashboard', [DashboardController::class, 'showDashboard'])->name('dashboard');

// Authentication routes
Route::middleware('guest')->group(function () {
    // Login routes
    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login');

    // Register routes
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register');

    // Forgot password routes
    Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])->name('password.request');
    Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])->name('password.email');
});

// Logout route (protected by Sanctum authentication)
Route::middleware('auth:sanctum')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Profile routes (protected by Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// MQTT routes (protected by Sanctum authentication)
Route::middleware('auth:sanctum')->group(function () {
    // Send message via MQTT (using DashboardController)
    Route::post('/send-message', [DashboardController::class, 'sendMessage'])->name('send.message');
});

// Include authentication routes (Sanctum login/register)
require __DIR__.'/auth.php';
