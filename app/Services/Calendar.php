<?php

namespace App\Services;

use App\Services\Scheduler;
use App\Services\Timeslot;
use Carbon\Carbon;

class Calendar
{
    public function __construct(
        protected Scheduler $scheduler,
        protected Timeslot $timeslot
    )
    {

    }

    public function buildCalendar(Carbon $dateTime): array
    {
        $prevNavLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month-1, 1, $dateTime->year)))->format('Y-m-d');
        $nextNavLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month+1, 1, $dateTime->year)))->format('Y-m-d');

        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');

        $firstDayOfMonth = mktime(0, 0, 0, $dateTime->month, 1, $dateTime->year);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'];

        $startOfCalendar =  $dateTime->copy()->firstOfMonth();
        $endOfCalendar = $dateTime->copy()->lastOfMonth();

        return [
            'prevNavLink' => $prevNavLink,
            'nextNavLink' => $nextNavLink,
            'daysOfWeek' => $daysOfWeek,
            'dayOfWeek' => $dayOfWeek,
            'startOfCalendar' => $startOfCalendar,
            'endOfCalendar' => $endOfCalendar
        ];
    }

    public function buildTimeslots(Carbon $dateTime, string $timezone): array
    {
        $retrive = $this->timeslot->retrive($dateTime);

        $list = $this->timeslot->list($dateTime, $timezone);

        return [
            'retrive' => $retrive,
            'list' => $list
        ];
    }
}
