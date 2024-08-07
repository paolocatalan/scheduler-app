<?php

namespace App\Services;

use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Facades\Cookie;

class Timeslot
{
    private CarbonImmutable $currentDateTime;

    public function __construct()
    {
        $this->currentDateTime = CarbonImmutable::now(config('app.timezone_display'));
    }

    public function getBookedTimeslots(): array
    {
      $bookings = Booking::where('schedule_call', '>', $this->currentDateTime)->get();
      $bookedTimeslots = array();
      foreach ($bookings as $booking) {
          $bookedDate = new Carbon($booking->schedule_call, $booking->timezone);
          if ($bookedDate->format('Y-m-d') == $this->dateTime->format('Y-m-d')) {
              $bookedTimeslot = new Carbon($booking->schedule_call, $booking->timezone);
              $bookedTimeslots[] = $bookedTimeslot->format('H:i:s');
          } else {
              $bookedTimeslots[] = '';
          }
      }
  
      return $bookedTimeslots;
    }

    public function timeslots(): array
    {
      $openTime = self::timezoneConverter($this->dateTime->format('Y-m-d') . ' 9:00:00', $this->timezone, config('app.timezone_display'));
      $closeTime = self::timezoneConverter($this->dateTime->format('Y-m-d') . ' 17:00:00', $this->timezone, config('app.timezone_display'));
  
      $timeslots = array();
      for ($t = $openTime->timestamp; $t < $closeTime->timestamp; $t+=1800) {
          if ($this->dateTime->isToday()) {
              if ($t < $this->currentDateTime->timestamp) continue;
          }
          $timeslots[] = Carbon::createFromTimestamp($t, $this->timezone)->format('H:i:s');
      }
  
      return $timeslots;
    }
}
