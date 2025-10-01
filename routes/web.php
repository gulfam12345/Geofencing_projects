<?php

use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\GeofenceController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\UserController;



Route::get('/', [DashboardController::class, 'index'])->name('dashboard')->middleware('auth');


// Registration
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register.form');
Route::post('/register', [AuthController::class, 'register'])->name('register');

// Login
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login/otp/send', [AuthController::class, 'sendLoginOtp'])->name('login.otp.send');
Route::get('/login/otp', [AuthController::class, 'showLoginOtpForm'])->name('login.otp.form');
Route::post('/login/otp/verify', [AuthController::class, 'verifyLoginOtp'])->name('login.otp.verify');
Route::middleware('auth')->group(function () {
    Route::get('/users', [UserController::class, 'index'])->name('users.index');
});
// Dashboard
Route::get('/dashboard', function () {
    $user = Auth::user();
    return view('dashboard', compact('user'));
})->middleware('auth')->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware('auth')
    ->name('dashboard');


// Logout
Route::post('/logout', function () {
    Auth::logout();
    return redirect()->route('login');
})->name('logout');

// Protected Routes
Route::middleware('auth')->group(function () {

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile.index');
    Route::post('/profile/update', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile/delete', [ProfileController::class, 'destroy'])->name('profile.delete');

    // Geofencing Page
    Route::get('/geofences/page', function() {
        $user = Auth::user();
        return view('geofences.index', compact('user'));
    })->name('geofences.page');

    // Geofencing CRUD
    Route::get('/geofences', [GeofenceController::class, 'index']);
    Route::post('/geofences', [GeofenceController::class, 'store']);
    Route::put('/geofences/{id}', [GeofenceController::class, 'update']);
    Route::delete('/geofences/{id}', [GeofenceController::class, 'destroy']);
});
