<?php

namespace App\Services;

use DateTime;
use Carbon\Carbon;
use Exception;

class Scheduler
{
    public function checkDate($date): Carbon
    {
        if ($this->parseDate($date) == false) {
            return now(config('app.timezone_display'));
        }

        $dateTime = new Carbon($date, config('app.timezone_display'));

        if ($dateTime->isPast()) {
            $dateTime = now(config('app.timezone_display'));
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

    public function parseDate($date): bool
    {
        DateTime::createFromFormat('Y-m-d', $date);
        $errors = DateTime::getLastErrors();

        if (!empty($errors)) {
            return false;
        }

        return true;

    }

    public function convertTimezone($dateTime, $timezoneTo, $timezoneFrom): Carbon
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $timezoneFrom)->setTimezone($timezoneTo);
    }
}