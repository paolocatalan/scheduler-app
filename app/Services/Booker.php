<?php

namespace App\Services;

use Carbon\Carbon;
use Spatie\GoogleCalendar\Event;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use App\Mail\BookingSuccessMail;

class Booker
{
    public function __construct(
        public array $bookingInfo
    )
    {
        $this->bookingInfo = $bookingInfo;
    }

    public function process(): bool
    {
        try {
            $meetingLink = $this->create('meetLink');

            if ($meetingLink == null) {
                Throw new \Google\Service\Exception('Something went wrong with Google services'); 
            }
        
        } catch (\Google\Service\Exception $exception) {
            Log::error($exception->getMessage());

            $meetingLink = $this->create();

        } catch (Throwable $th) {
            // dd(get_class($exception));
            Log::critical('Google services error: '. $th->getMessage());

            $meetingLink = null;
        }

        $mail = Mail::to($this->bookingInfo['email'])->send(new BookingSuccessMail($this->bookingInfo['name'], $this->bookingInfo['schedule_call'], $this->bookingInfo['timezone'], $meetingLink));

        if ($mail) {
            return true;
        }

        return false;

    }

    public function create($link = null)
    {
        $event = new Event;
        $event->name = 'Introduction and Diagnosis';
        $event->startDateTime = Carbon::parse($this->bookingInfo['schedule_call'], $this->bookingInfo['timezone']);
        $event->endDateTime = Carbon::parse($this->bookingInfo['schedule_call'], $this->bookingInfo['timezone'])->addMinute(30);
        $event->start->timeZone = $this->bookingInfo['timezone'];
        $event->end->timeZone = $this->bookingInfo['timezone'];
        $event->description = '<p>Confirmed meeting with '. $this->bookingInfo['name'] . '.</p><p>Notes:</p>' . $this->bookingInfo['notes'];

        if ($link == 'meetLink') {
            // requires Google Workspace subscription
            $event->addAttendee(['email' => $this->bookingInfo['email']]);
            $event->addMeetLink();
            $calendarEvent = $event->save();

            return $calendarEvent->hangoutLink;
        }

        $calendarEvent = $event->save();
        return $calendarEvent->htmlLink;
    }


}
