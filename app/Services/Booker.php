<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;
use Throwable;
use Illuminate\Support\Facades\Log;

class Booker
{
    public function process($date, $timezone, $name, $email, $notes)
    {
        try {
            $link = $this->create($date, $timezone, $name, $email, $notes, true);

            if ($link == null) {
                Throw new \Google\Service\Exception('Something went wrong with Google Services'); 
            }

            return $link;
        
        } catch (\Google\Service\Exception $exception) {
            // dd(get_class($exception));
            Log::error($exception->getMessage());

            $link = $this->create($date, $timezone, $name, $email, $notes, false);

            return $link;

        } catch (Throwable $exception) {

            Log::warning('Google services error: '. $exception->getMessage());

            return null;

        }
    }

    public function create($date, $timezone, $name, $email, $notes, bool $meetLink)
    {
        $event = new Event;
        $event->name = 'Introduction and Diagnosis';
        $event->startDateTime = Carbon::parse($date, $timezone);
        $event->endDateTime = Carbon::parse($date, $timezone)->addMinute(30);
        $event->start->timeZone = $timezone;
        $event->end->timeZone = $timezone;
        $event->description = '<p>Confirmed meeting with '. $name . '</p><p>Notes:</p>' . $notes;

        if ($meetLink) {
            // requires Google Workspace subscription
            $event->addAttendee(['email' => $email]);
            $event->addMeetLink();
            $calendarEvent = $event->save();

            return $calendarEvent->hangoutLink;
        }

        $calendarEvent = $event->save();
        return $calendarEvent->htmlLink;
    }


}
