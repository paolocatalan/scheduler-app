<?php

namespace App\Services;

use Carbon\Carbon;

class Scheduler
{
    private Carbon $currentDateTime;

    public function __construct()
    {
        $this->currentDateTime = Carbon::now(config('app.timezone_display'));
    }

    public function checkDate($date)
    {
        $dateTime = new Carbon($date, config('app.timezone_display'));

        //check for past days
        if ($this->currentDateTime > $dateTime) {
            $dateTime = $this->currentDateTime;
        }

        $unavailableDates = $this->unavailableDates();

        $isUnavailableOrDayOff = function ($dateTime) use ($unavailableDates) {
            return in_array($dateTime->format('Y-m-d'), $unavailableDates) || $dateTime->dayOfWeek == Carbon::SUNDAY;
        };

        while ($isUnavailableOrDayOff($dateTime)) {
            $dateTime->addDay();
        }

        return $dateTime;
    }

    public function unavailableDates()
    {
        return array(
            '2024-08-07'
        );
    }
}