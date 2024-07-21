<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\RegisteredUserController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\ProjectController;
use App\Http\Controllers\BookingController;
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


Route::get('/sandbox', function() {

    function randomDate() {
        $randomTimestamp = rand(1721395626, 1752931626);
        $dateTime = Booker::dateChecker(date('Y-m-d', $randomTimestamp));
        $randomTimezone = timezone_identifiers_list()[array_rand(timezone_identifiers_list())];
        $dateTime->setTimezone($randomTimezone);
        return $dateTime;
    }

    $dateTime = randomDate();

    $calendar = new Booker('2024', '08', '2024-08-20', 'Europe/Kyiv');
    
    
    // // set accessible for private method
    // $reflector = new ReflectionClass($calendar);

    // $function  = $reflector->getMethod('timeslots');

    $bookedDates = Booker::bookedDates();
    dd($bookedDates[array_rand($bookedDates)]);

    // $startDateTime = '2024-12-18 11:00:00';
    // $timezone = 'Europe/Warsaw';
    // $attendee = 'samaltman@openai.com';

    // $events = Spatie\GoogleCalendar\Event::get();
    // $event->addAttendee(['email' => $attendee]);
    // $event->save();
    // return response()->json(['message' => 'saved event.']);
    // $event = Spatie\GoogleCalendar\Event::find('3lvkn81opdd59o121c3a466un8');
    // $event = Spatie\GoogleCalendar\Event::find('4cp2otegsk1ik18jedb09u8agc');

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