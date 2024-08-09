<?php

namespace App\Services;

use Carbon\Carbon;

class Calendar
{
    public function __construct(
        protected Scheduler $scheduler,
        protected Timeslot $timeslot,
        protected Timezone $timezone
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

        $retriveTimeslot = $this->timeslot->retrive($dateTime);
        $listTimeslot = $this->timeslot->list($dateTime, $this->timezone->select());

        return [
            'prevNavLink' => $prevNavLink,
            'nextNavLink' => $nextNavLink,
            'daysOfWeek' => $daysOfWeek,
            'dayOfWeek' => $dayOfWeek,
            'startOfCalendar' => $startOfCalendar,
            'endOfCalendar' => $endOfCalendar,
            'retriveTimeslot' => $retriveTimeslot,
            'listTimeslot' => $listTimeslot,
            'timezone' => $this->timezone->select()
        ];
    }
}
