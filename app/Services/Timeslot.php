<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;

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
              $bookedTimeslot = new Carbon($booking->schedule_call, $booking->timezone);
              $retriveBookings[] = $bookedTimeslot->format('H:i:s');
          } else {
              $retriveBookings[] = '';
          }
      }

      return $retriveBookings;
    }

    public function list(Carbon $dateTime, string $timezone): array
    {
        $openTime = $this->scheduler->convertTimezone($dateTime->format('Y-m-d') . ' 9:00:00', $timezone, config('app.timezone_display'));
        $closeTime = $this->scheduler->convertTimezone($dateTime->format('Y-m-d') . ' 17:00:00', $timezone, config('app.timezone_display'));

        $timeslots = array();
        for ($t = $openTime->timestamp; $t < $closeTime->timestamp; $t+=1800) {
            if ($dateTime->isToday()) {
                if ($t < now()->timestamp) continue;
            }
            $timeslots[] = Carbon::createFromTimestamp($t, $timezone)->format('H:i:s');
        }

        return $timeslots;
    }
}
