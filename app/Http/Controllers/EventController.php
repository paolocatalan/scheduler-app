<?php

namespace App\Http\Controllers;

use App\Services\Calendar;
use App\Services\Scheduler;
use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;

class EventController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            'auth'
        ];
    }

    public function index()
    {
        $events = Event::get(now(config('app.timezone_display')), now(config('app.timezone_display'))->addMonth(2));
        return view('events.index', [
            'events' => $events
        ]);
    }

    public function showCalendar(Request $request, Calendar $calendar, Scheduler $scheduler)
    {
        $dateTime = $scheduler->checkDate($request->date);



        return view('bookings.show', [
            'dateTime' => $dateTime,
            'buildCalendar' => $calendar->buildCalendar($dateTime)
        ]);
    }

    public function create()
    {
        // $startDateTime = '2024-12-18 11:00:00';
        // $timezone = 'Europe/Warsaw';
        // $attendee = 'samaltman@openai.com';

        // $events = Spatie\GoogleCalendar\Event::get();
        // $event->addAttendee(['email' => $attendee]);
        // $event->save();
        // return response()->json(['message' => 'saved event.']);
        // $event = Spatie\GoogleCalendar\Event::find('3lvkn81opdd59o121c3a466un8');
        $event = Event::find('d1r7j6pluv0c9gn9cgne9i4468');

        //     $event = new Spatie\GoogleCalendar\Event;
        //     $event->name = 'Intro and Diagnosis';
        //     $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
        //     $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
        //     $event->start->timeZone = $timezone;
        //     $event->end->timeZone = $timezone;
        //     $event->addAttendee(['email' => $attendee]);
        //     // $event->addMeetLink();
        //     $event->save();


        // $event = GoogleMeetController::create($eventTitle, $startDateTime, $timezone);
        // $htmlLink = \App\Services\BookingServices::calendarEvent($startDateTime, $timezone, $attendee);

        dd($event);
    }

    public function show(string $id)
    {
        $event = Event::find($id);
        return view('events.show', [
            'event' => $event
        ]);

    }

    public function destroy(string $id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect('/events')->with('message', 'Your schedule event has been deleted.');

    }

}
