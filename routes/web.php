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
})->name('test');

// registration
Route::get('/register', [RegistrationController::class, 'register'])->name('register');
Route::post('/register', [RegistrationController::class, 'registerRequest'])->name('register.post');

// login
Route::get('/login', [AuthController::class, 'login'])->name('login');
Route::post('/login', [AuthController::class, 'loginRequest'])->name('login.post');

// rsvp
Route::post('/rsvp', [RsvpController::class, 'rsvpRequest'])->name('rsvp.post');
Route::get('/rsvp/handle', [RsvpController::class, 'handleRsvp'])->name('rsvp.handle');


// event
Route::get('/!{event_id}', [EventController::class, 'event'])->name('event');

// events
Route::get('/@{username}', [EventController::class, 'events'])->name('index');

// create event
Route::get('/create', [EventController::class, 'create'])->name('event.create');
Route::get('/create/handle', [EventController::class, 'handleCreateRequest'])->name('event.create.handle');
Route::post('/create', [EventController::class, 'createRequest'])->name('event.create.post');

// auth middleware
Route::middleware(['auth'])->group(function () {
    // logout
    Route::post('/logout', [AuthController::class, 'logoutRequest'])->name('logout.post');

    // account
    Route::get('/settings', [SettingsController::class, 'settings'])->name('settings');
    Route::post('/settings', [SettingsController::class, 'settingsRequest'])->name('settings.post');

    // edit event
    Route::get('/edit/!{event_id}', [EventController::class, 'edit'])->name('event.edit');
    Route::post('/edit/!{event_id}', [EventController::class, 'editRequest'])->name('event.edit.post');

    // delete event
    Route::post('/delete', [EventController::class, 'delete'])->name('event.delete.post');

    // comment
    Route::post('/add/comment', [CommentController::class, 'commentRequest'])->name('comment.post');
    Route::post('/delete/comment', [CommentController::class, 'commentDeleteRequest'])->name('comment.delete.post');
    Route::get('/edit/comment/{comment_id}', [CommentController::class, 'commentEdit'])->name('comment.edit');
    Route::post('/edit/comment/{comment_id}', [CommentController::class, 'commentEditRequest'])->name('comment.edit.post');
});
