<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\SettingsController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\RsvpController;
use App\Http\Controllers\CommentController;

// test page
// Route::get('/', function () {
//     return view('test');
// });

// registration
Route::get('/register', [RegistrationController::class, 'register'])->name('register');
Route::post('/register', [RegistrationController::class, 'registerRequest'])->name('register.post');

// login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginRequest'])->name('login.post');

// rsvp
Route::post('/rsvp/!{event_id}', [RsvpController::class, 'rsvpRequest'])->name('rsvp.post');

// event
Route::get('/!{event_id}', [EventController::class, 'event'])->name('event');

// account
Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');

// events
Route::get('/@{username}', [EventController::class, 'events'])->name('index');

// auth middleware
Route::middleware(['auth'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logoutRequest'])->name('logout.post');

    // create event
    Route::get('/create', [EventController::class, 'create'])->name('event.create');
    Route::post('/create', [EventController::class, 'createRequest'])->name('event.create.post');

    // edit event
    Route::get('/!{event_id}/edit', [EventController::class, 'edit'])->name('event.edit');
    Route::post('!{event_id}/edit', [EventController::class, 'editRequest'])->name('event.edit.post');

    // delete event
    Route::post('/{event_id}/delete', [EventController::class, 'delete'])->name('event.delete.post');

    // comment
    Route::post('/!{event_id}', [CommentController::class, 'commentRequest'])->name('comment.post');
    Route::post('/!{event_id}', [CommentController::class, 'commentDeleteRequest'])->name('comment.delete.post');
    Route::post('/!{event_id}', [CommentController::class, 'commentEditRequest'])->name('comment.edit.post');
});
