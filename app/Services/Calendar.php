<?php

namespace App\Services;

use App\Services\Scheduler;
use App\Services\Timeslot;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class Calendar
{
    public function __construct(
        private Carbon $currentDateTime,
        protected Scheduler $scheduler,
        protected Timeslot $timeslot
    )
    {
        $this->currentDateTime = Carbon::now(config('app.timezone_display'));
    }

    public function buildCalendar(Carbon $dateTime): array
    {
        $prevNavLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month-1, 1, $dateTime->year)))->format('Y-m-d');
        $nextNavLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month+1, 1, $dateTime->year)))->format('Y-m-d');

        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        $firstDayOfMonth = mktime(0, 0, 0, $dateTime->month, 1, $dateTime->year);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'];

        return [
            'prevNavLink' => $prevNavLink,
            'nextNavLink' => $nextNavLink,
            'daysOfWeek' => $daysOfWeek,
            'dayOfWeek' => $dayOfWeek,
            'currentDay' => 1
        ];
    }

    public function dayInCarbon(Carbon $dateTime, $currentDay): Carbon
    {
        return Carbon::createFromDate($dateTime->year, $dateTime->month, $currentDay, config('app.timezone_display'));
    }

    public function buildTimeslots(Carbon $dateTime, string $timezone): array
    {
        $listTimeslots = $this->timeslot->retrive($dateTime, $this->currentDateTime);

        $convertTimeslots = $this->timeslot->convert($dateTime, $this->currentDateTime, $timezone);

        return [
            'listTimeslots' => $listTimeslots,
            'convertTimeslots' => $convertTimeslots
        ];
    }
}
