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

    public function build(string $date)
    {
        $dateTime = $this->scheduler->checkDate($date);
        $currentDateTime = Carbon::now(config('app.timezone_display'));

        $bookedDates  = $this->scheduler->unavailableDates();

        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $firstDayOfMonth = mktime(0, 0, 0, $dateTime->month, 1, $dateTime->year);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'];
        $currentDay = 1;

        $disablePastMonth = ($dateTime->month == $currentDateTime->month) ? ' disabled="disabled"' : '';
        $previousMonthLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month-1, 1, $dateTime->year)));
        $nextMonthLink = $this->scheduler->checkDate(date('Y-m-d', mktime(0, 0, 0, $dateTime->month+1, 1, $dateTime->year)));
    
        $calendar = '<div class="month-header"><h2>'. $dateTime->monthName . ' '. $dateTime->year . '</h2>';
        $calendar .= '<nav>';
        $calendar .= '<button hx-get="/schedule-a-call/?date=' . $previousMonthLink->format('Y-m-d') .'" hx-push-url="true"'. $disablePastMonth .' hx-target="#content-area" hx-select=".calendar">&lsaquo;</button>';
        $calendar .= '<button hx-get="/schedule-a-call/?date=' . $nextMonthLink->format('Y-m-d') .'" hx-push-url="true" hx-target="#content-area" hx-select=".calendar">&rsaquo;</button>';
        $calendar .= '</nav></div>';

        $calendar .= '<table><tr>';
        foreach($daysOfWeek as $d) {
            $calendar .= '<th>' . $d . '</th>';
        }
        $calendar .= '</tr><tr>';

        if ($dayOfWeek > 0) {
            for ($j=0; $j < $dayOfWeek; $j++) {
                $calendar .= '<td></td>';
            }
        }

        while ($currentDay <= $dateTime->daysInMonth) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= '</tr><tr>';
            }

            $date = Carbon::createFromDate($dateTime->year, $dateTime->month, $currentDay, config('app.timezone_display'));
            $dateRequest = (isset($_GET['date'])) ? $_GET['date'] : '';
            $activeLink = ($dateRequest == $date->format('Y-m-d')) ? ' class="active"' : '';
            $todayIndicator = ($currentDateTime->format('Y-m-d') == $date->format('Y-m-d')) ? ' class="today"' : '';

            if ($date < $currentDateTime || $date->format('D') == 'Sun') {
                $calendar .= '<td class="not-available"><span>'. $currentDay .'</span>';
            } elseif (in_array($date->format('Y-m-d'), $bookedDates)) {
                $calendar .= '<td class="not-available"><span>'. $currentDay .'</span>';
            } else {
                $calendar .= '<td'. $activeLink .'><a hx-get="/schedule-a-call/?date=' . $date->format('Y-m-d') .'" hx-push-url="true"  hx-target="#content-area" hx-select=".calendar"><span'. $todayIndicator .'>' . $currentDay .'</span></a>';
            }

            $calendar .= '</td>';
            $currentDay++;
            $dayOfWeek++;
        }

        if ($dayOfWeek != 7) {
            $remainingDays = 7 - $dayOfWeek;
            for ($i=0;  $i < $remainingDays; $i++) {
                $calendar .= '<td></td>';
            }

        }

        $calendar .= '</tr>';
        $calendar .= '</table>';

        return $calendar;
    }


}
