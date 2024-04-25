<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\CommentController;

// test page
Route::get('/', function () {
    return view('test');
});

// registration
Route::get('/register', [RegistrationController::class, 'register'])->name('register');
Route::post('/register', [RegistrationController::class, 'registerRequest'])->name('register.post');

// login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginRequest'])->name('login.post');

// rsvp
Route::post('/{id}', [RsvpController::class, 'rsvpRequest'])->name('rsvp.post');

// event
Route::get('/{id}', [EventController::class, 'event'])->name('event');

// account
Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');

// auth middleware
Route::middleware(['auth'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logoutRequest'])->name('logout.post');

    // events
    Route::get('/@{username}', [EventController::class, 'events'])->name('events');

    // create event
    Route::get('/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/create', [EventController::class, 'createRequest'])->name('event.create.post');

    // edit event
    Route::get('/edit/{id}', [EventController::class, 'edit'])->name('event.edit');
    Route::post('/edit/{id}', [EventController::class, 'editRequest'])->name('event.edit.post');

    // delete event
    Route::post('/delete/{id}', [EventController::class, 'delete'])->name('event.delete.post');

    // comment
    Route::post('/{id}', [CommentController::class, 'commentRequest'])->name('comment.post');
    Route::post('/{id}', [CommentController::class, 'commentDeleteRequest'])->name('comment.delete.post');
    Route::post('/{id}', [CommentController::class, 'commentEditRequest'])->name('comment.edit.post');
});
