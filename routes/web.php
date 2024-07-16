<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BookingController;
use App\Http\Helpers\Booker;
use Illuminate\Foundation\Auth\EmailVerificationRequest;
use Illuminate\Support\Facades\Cookie;

Route::get('/', function() {
    return view('sections.static.home');
})->name('home');

Route::get('/hire-me', function() {
    return view('sections.static.hire-me');
});

Route::resource('projects', ProjectController::class);
Route::get('/login', [SessionController::class, 'create'])->middleware('guest')->name('login');

Route::middleware(['auth'])->group(function () {
    Route::post('/login', [SessionController::class, 'store']);
    Route::post('/logout', [SessionController::class, 'destroy']);
    // under construction
    Route::get('/register', [RegisteredUserController::class, 'create']);
    Route::post('/register', [RegisteredUserController::class, 'store']);
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




Route::post('/set-timezone', function(Request $request) {
    $timezoneCookie = Illuminate\Support\Facades\Cookie::make('timezone', $request->timezone, 60);
    return back()->withCookie($timezoneCookie);
});

Route::get('/test', function(Request $request) {
    
    // dd(Carbon\Carbon::now()->inUserTimezone()->format('Y-m-d'));

    $dateChecked = Booker::dateChecker('2023-08-01');
    dd($dateChecked);

    // $date = new Carbon\CarbonImmutable('2024-07-17');
    // $now = Carbon\CarbonImmutable::now();

    // $dateTime = new Carbon\Carbon($date, config('app.timezone_display'));

    // $timezoneCookie = Cookie::get('timezone');

    // $dateTime = $now->inUserTimezone();
    // $timezone = $dateTime->tzName;
    // return view('test', [
    //     'dateTime' => $dateTime,
    //     'timezone' => $timezone,
    //     'date' => $date->inUserTimezone(),
    //     'timezoneCookie' => $timezoneCookie
    // ]);



    // $startDateTime = '2024-12-18 11:00:00';
    // $timezone = 'Europe/Warsaw';
    // $attendee = 'samaltman@openai.com';

    // $events = Spatie\GoogleCalendar\Event::get();
    // dd($events);
    // $event = Event::find('3lvkn81opdd59o121c3a466un8');
    // $event = Event::find('4cp2otegsk1ik18jedb09u8agc');
    // $event->addAttendee(['email' => $attendee]);
    // $event->save();
    // return response()->json(['message' => 'saved event.']);

        // $event = new Spatie\GoogleCalendar\Event;
        // $event->name = 'Intro and Diagnosis';
        // $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
        // $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
        // $event->start->timeZone = $timezone;
        // $event->end->timeZone = $timezone;
        // $event->addAttendee(['email' => $attendee]);
        // // $event->addMeetLink();
        // $event->save();


    // $event = GoogleMeetController::create($eventTitle, $startDateTime, $timezone);
    // $htmlLink = \App\Services\BookingServices::calendarEvent($startDateTime, $timezone, $attendee);

    // dd($htmlLink);

});