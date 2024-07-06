<?php

namespace App\Http\Controllers;

use App\Services\BookingServices;
use Illuminate\Http\Request;
use Spatie\GoogleCalendar\Event;

class GoogleMeetController extends Controller
{

  public function index() {
    $startDateTime = '2024-12-14 11:00:00';
    $timezone = 'Europe/Warsaw';
    $attendee = 'samaltman@openai.com';
    // $events = Event::get();
    // $event = Event::find('3lvkn81opdd59o121c3a466un8');
    // $event = Event::find('4cp2otegsk1ik18jedb09u8agc');
    // $event = new Event;
    // $event->name = 'Intro and Diagnosis';
    // $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
    // $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
    // $event->start->timeZone = $timezone;
    // $event->end->timeZone = $timezone;
    // $event->addAttendee(['email' => $attendee]);
    // $event->save();
    // return response()->json(['message' => 'saved event.']);

    // $event = GoogleMeetController::create($eventTitle, $startDateTime, $timezone);
    $htmlLink = BookingServices::calendarEvent($startDateTime, $timezone, $attendee);

    dd($htmlLink);

  }

  public function create($eventTitle, $startDateTime, $timezone, $attendee = null)
  {
    try {
      $event = new Event;
      $event->name = $eventTitle;
      $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
      $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
      $event->start->timeZone = $timezone;
      $event->end->timeZone = $timezone;
      // requires Google Workspace subscription
      // $event->addAttendee(['email' => $attendee]);
      // $event->addMeetLink();
      // $event->description = 'Confirmed Meeting with '. $attendee;
      $calendarEvent = $event->save();
            
      // $calendarEvent->hangoutLink;
      // $calendarEvent->id;

      return $calendarEvent->htmlLink;

    } catch (\Throwable $e) {
      report($e);
    }
  }

}
