<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;

class BookingService
{
    public static function calendarEvent($startDateTime, $timezone, $attendee = null)
    {
        try {
            $event = new Event;
            $event->name = 'Introduction and Diagnosis';
            $event->startDateTime = Carbon::parse($startDateTime);
            $event->endDateTime = Carbon::parse($startDateTime)->addMinute(30);
            $event->start->timeZone = $timezone;
            $event->end->timeZone = $timezone;
            $event->description = 'Confirmed meeting with '. $attendee;
            // requires Google Workspace subscription
            // $event->addAttendee(['email' => $attendee]);
            // $event->addMeetLink();

            $calendarEvent = $event->save();
            // $calendarEvent->hangoutLink;
            // $calendarEvent->id;

            return $calendarEvent->htmlLink;
        
        } catch (\Google\Service\Exception $e) {
        
            report('Something went wrong with Google Services');
            report($e);
        
        } catch (\Throwable $exception) {
        
            report($exception);
            // dd(get_class($exception));
        }
    }

    public function booked()
    {
      return array(
          '2024-09-02'
      );
    }

}
