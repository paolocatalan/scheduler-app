<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\EventController;
use App\Http\Helpers\Booker;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

Route::get('/', function() {
    return view('sections.static.home');
})->name('home');

Route::get('/hire-me', function() {
    return view('sections.static.hire-me');
});

Route::resource('projects', ProjectController::class);
Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');
Route::post('/login', [SessionController::class, 'store']);
Route::get('/register', [RegisteredUserController::class, 'create']);
Route::post('/register', [RegisteredUserController::class, 'store']);

Route::middleware(['auth'])->group(function () {
    Route::get('/events', [EventController::class, 'index']);
    Route::get('/events/create', [EventController::class, 'create']);
    Route::get('/events/{id}', [EventController::class, 'show']);
    Route::delete('/events/{id}', [EventController::class, 'destroy'])->name('events.destroy');
    Route::post('/logout', [SessionController::class, 'destroy']);
});

Route::controller(BookingController::class)->group(function () {
    Route::get('/schedule-a-call', 'index');
    Route::get('/schedule-a-call/introduction', 'create');
    Route::post('/schedule-a-call', 'store');
    Route::get('/schedule-a-call/success', 'success')->name('booking.success');
    Route::post('/schedule-a-call/timezone', 'setTimezone')->name('booking.timezone');
});

Route::get('/email/verify/{id}/{hash}', function(EmailVerificationRequest $request) {
    $request->fulfill();
    return redirect('/projects')->with('message', 'Email address has been verified.');
})->middleware(['auth', 'signed'])->name('verification.verify');
Route::post('/email/verification-notification', function(Request $request) {
    $request->user()->sendEmailVerificationNotification();
    return back()->with('message', 'Verification link sent!');
})->middleware(['auth', 'throttle:6,1'])->name('verification.send');
