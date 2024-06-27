<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Spatie\GoogleCalendar\Event;

class GoogleMeetController extends Controller
{
  public function index() {
    $event_name = 'Introduction and Diagnosis';
    $startDateTime = '2024-09-09 10:00:00';
    $timezone = 'Europe/Warsaw';
    GoogleMeetController::createEvent($event_name, $startDateTime, $timezone);

    return 'added Throw';
    // $events = Event::get();
    // $event = Event::find('3lvkn81opdd59o121c3a466un8');
    // $timezone = 'Europe/Warsaw';
    // $event = new Event;
    // $event->name = $name;
    // $email = 'samaltman@openai.com';
    // $startDate = '2024-08-16 10:00:00';
    // $event->startDateTime = \Carbon\Carbon::parse($startDate);
    // $event->endDateTime = \Carbon\Carbon::parse($startDate)->addMinute(30);
    // $event->start->timeZone = $timezone;
    // $event->end->timeZone = $timezone;

    // $event->save();
    // dd($event);

    // return response()->json(['message' => 'saved event.']);
  }

  public static function createEvent($name, $startDateTime, $timezone, $attendee = null)
  {
    try {
      $event = new Event;
      $event->name = $name;
      $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
      $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
      $event->start->timeZone = $timezone;
      $event->end->timeZone = $timezone;
      // requires Google Workspace subscription
      // $event->addAttendee(['email' => $attendee]);
      // $event->addMeetLink();
      // $event->description = 'Confirmed Meeting with '. $attendee;
      $event->save();
    } catch (\Throwable $e) {
      report($e);
    }
  }
}
