<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function() {
    return view('home');
})->name('home');

Route::get('/hire-me', function() {
    return view('hire-me');
});

Route::get('/calendar', [EventController::class, 'showCalendar']);

Route::resource('projects', ProjectController::class);
Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::post('/logout', [SessionController::class, 'destroy'])->middleware('auth');

Route::controller(BookingController::class)->group(function () {
    Route::get('/schedule-a-call', 'index');
    Route::get('/schedule-a-call/introduction', 'create');
    Route::post('/schedule-a-call', 'store');
    Route::get('/schedule-a-call/success', 'success')->name('booking.success');
    Route::post('/timezone', 'setTimezone')->name('booking.timezone');
});

Route::controller(EventController::class)->group(function () {
    Route::get('/events', 'index');
    Route::get('/events/create', 'create');
    Route::get('/events/{id}', 'show');
    Route::delete('/events/{id}', 'destroy')->name('events.destroy');
});

 // close the registration for now to save resources
Route::get('/register', [RegisteredUserController::class, 'create'])->middleware('isAdmin');
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::get('/email/verify', function () {
    return view('auth.verify-email');
})->middleware('auth')->name('verification.notice');

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    $request->fulfill();

    return redirect('/projects')->with('message', 'Email address has been verified.');
})->middleware(['auth', 'signed'])->name('verification.verify');

Route::post('/email/verification-notification', function(Request $request) {
    $request->user()->sendEmailVerificationNotification();

    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
