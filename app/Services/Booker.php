<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;
use Throwable;
use Illuminate\Support\Facades\Log;

class Booker
{
    public function __construct(
        public array $bookingInfo
    )
    {
        $this->bookingInfo = $bookingInfo;
    }

    public function process()
    {
        try {
            $link = $this->create($this->bookingInfo, true);

            if ($link == null) {
                Throw new \Google\Service\Exception('Something went wrong with Google services'); 
            }

            return $link;
        
        } catch (\Google\Service\Exception $exception) {
            // dd(get_class($exception));
            Log::error($exception->getMessage());

            $link = $this->create($this->bookingInfo, false);

            return $link;

        } catch (Throwable $exception) {

            Log::critical('Google services error: '. $exception->getMessage());

            return 'https://meet.google.com/hzd-auas-xnp';

        }
    }

    public function create(array $bookingInfo, bool $meetLink)
    {
        $event = new Event;
        $event->name = 'Introduction and Diagnosis';
        $event->startDateTime = Carbon::parse($bookingInfo['schedule_call'], $bookingInfo['timezone']);
        $event->endDateTime = Carbon::parse($bookingInfo['schedule_call'], $bookingInfo['timezone'])->addMinute(30);
        $event->start->timeZone = $bookingInfo['timezone'];
        $event->end->timeZone = $bookingInfo['timezone'];
        $event->description = '<p>Confirmed meeting with '. $bookingInfo['name'] . '.</p><p>Notes:</p>' . $bookingInfo['notes'];

        if ($meetLink) {
            // requires Google Workspace subscription
            $event->addAttendee(['email' => $bookingInfo['email']]);
            $event->addMeetLink();
            $calendarEvent = $event->save();

            return $calendarEvent->hangoutLink;
        }

        $calendarEvent = $event->save();
        return $calendarEvent->htmlLink;
    }


}
