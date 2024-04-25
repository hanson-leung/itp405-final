<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RsvpController;

// test page
Route::get('/test', [ProfileController::class, 'index'])->name('index');

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

    // events
    Route::get('/', [EventController::class, 'events'])->name('events');

    // create event
    Route::get('/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/create', [EventController::class, 'createRequest'])->name('event.create.post');

    // edit event
    Route::get('/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    Route::post('/edit/{id}', [EventController::class, 'editRequest'])->name('event.edit.post');

    // delete event
    Route::post('/delete/{id}', [EventController::class, 'delete'])->name('event.delete');

    // rsvp
    Route::post('/{id}', [RsvpController::class, 'rsvpRequest'])->name('rsvp.post');
});

// event
Route::get('/{id}', [EventController::class, 'event'])->name('event');
