<?php

namespace App\Services;

use Spatie\GoogleCalendar\Event;

class BookingServices
{
    public static function calendarEvent($startDateTime, $timezone, $attendee = null)
    {
        try {
            $event = new Event;
            $event->name = 'Introduction and Diagnosis';
            $event->startDateTime = \Carbon\Carbon::parse($startDateTime);
            $event->endDateTime = \Carbon\Carbon::parse($startDateTime)->addMinute(30);
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
        
        } catch (\Google\Service\Exception $exception) {
        
            report('Something went wrong with Google Services');
            report($exception);
        
        } catch (\Throwable $exception) {
        
            report($exception);
            // dd(get_class($exception));
        }
    }

}
