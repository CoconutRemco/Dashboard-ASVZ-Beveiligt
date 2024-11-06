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

// Dashboard route (protected by authentication and email verification)
Route::get('/dashboard', [DashboardController::class, 'showDashboard'])->middleware(['auth', 'verified'])->name('dashboard');

// Authentication routes
Route::middleware('guest')->group(function () {
// Login routes
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
->name('login');
Route::post('/login', [AuthenticatedSessionController::class, 'store']);

// Register routes
Route::get('/register', [RegisteredUserController::class, 'create'])
->name('register');
Route::post('/register', [RegisteredUserController::class, 'store']);

// Forgot password routes
Route::get('/forgot-password', [PasswordResetLinkController::class, 'create'])
->name('password.request');
Route::post('/forgot-password', [PasswordResetLinkController::class, 'store'])
->name('password.email');
});

// Logout route (Laravel handles this automatically)
Route::middleware('auth')->post('/logout', [AuthenticatedSessionController::class, 'destroy'])
->name('logout');

// Profile routes (protected by authentication)
Route::middleware('auth')->group(function () {
Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// MQTT routes (protected by authentication)
Route::middleware('auth')->group(function () {
// Send message via MQTT (update to use DashboardController)
Route::post('/send-message', [DashboardController::class, 'sendMessage'])->name('send.message');
});

// Include authentication routes
require __DIR__.'/auth.php';
