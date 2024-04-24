<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;

// test page
Route::get('/', [ProfileController::class, 'index'])->name('index');

// registration
Route::get('/register', [RegistrationController::class, 'register'])->name('register');
Route::post('/register', [RegistrationController::class, 'registerRequest'])->name('register.post');

// login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginRequest'])->name('login.post');


// auth middleware
Route::middleware(['auth'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logoutRequest'])->name('logout.post');
});
