<?php

namespace App\Services;

use Carbon\Carbon;

class Scheduler
{
    public function checkDate(string $date): Carbon
    {
        $dateTime = new Carbon($date, config('app.timezone_display'));

        if (now() > $dateTime) {
            $dateTime = now();
        }

        $unavailableDates = array( '2024-09-19' );

        $isUnavailableOrDayOff = function ($dateTime) use ($unavailableDates) {
            return in_array($dateTime->format('Y-m-d'), $unavailableDates) || $dateTime->dayOfWeek == Carbon::SUNDAY;
        };

        while ($isUnavailableOrDayOff($dateTime)) {
            $dateTime->addDay();
        }

        return $dateTime;
    }

    public function convertTimezone($dateTime, $timezoneTo, $timezoneFrom): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $timezoneFrom)->setTimezone($timezoneTo);
    }
}