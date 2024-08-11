<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use App\Services\Scheduler;

class Timeslot
{
    public function __construct(protected Scheduler $scheduler)
    {

    }

    public function retrive(Carbon $dateTime): array
    {
      $bookings = Booking::where('schedule_call', '>', now(config('app.timezone_display')))->get();
      $retriveBookings = array();
      foreach ($bookings as $booking) {
          $bookedDate = new Carbon($booking->schedule_call, $booking->timezone);
          if ($bookedDate->format('Y-m-d') == $dateTime->format('Y-m-d')) {
              $retriveBookings[] = new Carbon($booking->schedule_call, $booking->timezone);
          }
      }

      return $retriveBookings;
    }

    public function list(Carbon $dateTime, string $timezone): array
    {
        $openTime = $this->scheduler->convertTimezone($dateTime->format('Y-m-d') . ' 9:00:00', $timezone, config('app.timezone_display'));
        $closeTime = $this->scheduler->convertTimezone($dateTime->format('Y-m-d') . ' 17:00:00', $timezone, config('app.timezone_display'));

        $listTimeslots = array();
        for ($t = $openTime->timestamp; $t < $closeTime->timestamp; $t+=1800) {
            if ($dateTime->isToday()) {
                if ($t < now()->timestamp) continue;
            }
            $listTimeslots[] = Carbon::createFromTimestamp($t, $timezone);
        }

        return $listTimeslots;
    }
}
