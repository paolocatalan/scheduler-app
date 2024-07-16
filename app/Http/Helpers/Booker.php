<?php

namespace App\Http\Helpers;

use App\Models\Booking;
use Carbon\Carbon;
use Carbon\CarbonImmutable;

class Booker
{
    public $year;
    public $month;
    public $date;
    public $timezone;
    private $dateTime;
    private $currentDateTime;
    private $bookedDates;

    function __construct($year, $month, $date, $timezone) {
        $this->year = $year;
        $this->month = $month;
        $this->date = $date;
        $this->timezone = $timezone;
        $this->dateTime = new CarbonImmutable($date, $timezone);
        $this->currentDateTime = CarbonImmutable::now(config('app.timezone_display'));
        $this->bookedDates = array('2024-08-01 00:00:00', '2024-08-02 00:00:00', '2024-08-03 00:00:00');
    }

    public static function dateChecker($date) {
        $dateTime = new Carbon($date, config('app.timezone_display'));
        $currentDateTime = Carbon::now(config('app.timezone_display'));
        //check for past days
        if ($currentDateTime > $dateTime) {
            $dateTime = $currentDateTime;
        }

        // check for days off
        if ($dateTime->dayOfWeek === Carbon::SUNDAY ) {
            $dateTime = $dateTime->addDay();
        }

        // // check for unavailable dates
        // $bookedDates = array('2024-08-01', '2024-08-02', '2024-08-03');
        // while (in_array($dateTime->format('Y-m-d'), $bookedDates)) {
        //     $dateTime = $dateTime->addDay();
        // }

        return $dateTime;
    }

    public static function timezoneConverter($dateTime, $timezoneTo, $timezoneFrom) {
        $dateTimeConverted = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $timezoneFrom)->setTimezone($timezoneTo);
        return $dateTimeConverted;
    }

    public function buildCalendar() {
        $currentDate = $this->currentDateTime->format('Y-m-d');
        $currentMonth = $this->currentDateTime->month;

        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $firstDayOfMonth = mktime(0, 0, 0, $this->month, 1, $this->year);
        $numberDays = date('t', $firstDayOfMonth);
        $dateComponents = getdate($firstDayOfMonth);
        $monthName = $dateComponents['month'];
        $dayOfWeek = $dateComponents['wday'];
        $monthMumeric = $dateComponents['mon'];
        $currentDay = 1;

        $disablePastMonth = ($monthMumeric == $currentMonth) ? ' disabled="disabled"' : '';
        $previousMonthLink = $this->dateChecker(date('Y-m-d', mktime(0, 0, 0, $this->month-1, 1, $this->year)));
        $nextMonthLink = $this->dateChecker(date('Y-m-d', mktime(0, 0, 0, $this->month+1, 1, $this->year)));

        $calendar = '<div class="month-header"><h2>'. $monthName . ' '. $this->year . '</h2>';
        $calendar .= '<nav>';
        $calendar .= '<button hx-get="/schedule-a-call/?year=' . date('Y', mktime(0, 0, 0, $this->month-1, 1, $this->year)) . '&month=' . date('m', mktime(0, 0, 0, $this->month-1, 1, $this->year)) . '&date=' . $previousMonthLink->format('Y-m-d') .'" hx-push-url="/schedule-a-call/?date=' . $previousMonthLink->format('Y-m-d') .'"'. $disablePastMonth .' hx-target="#content-area" hx-select=".calendar">&lsaquo;</button>';
        $calendar .= '<button hx-get="/schedule-a-call/?year=' . date('Y', mktime(0, 0, 0, $this->month+1, 1, $this->year)) . '&month=' . date('m', mktime(0, 0, 0, $this->month+1, 1, $this->year)) . '&date=' . $nextMonthLink->format('Y-m-d') .'" hx-push-url="/schedule-a-call/?date=' . $nextMonthLink->format('Y-m-d') . '" hx-target="#content-area" hx-select=".calendar">&rsaquo;</button>';
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

        while ($currentDay <= $numberDays) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= '</tr><tr>';
            }

            $date = Carbon::createFromDate($this->year, $this->month, $currentDay, config('app.timezone_display'));

            $dateRequest = (isset($_GET['date'])) ? $_GET['date'] : $currentDate;
            $activeLink = ($dateRequest == $date->format('Y-m-d')) ? ' class="active"' : '';
            $todayIndicator = ($currentDate == $date->format('Y-m-d')) ? ' class="today"' : '';

            if ($date < $this->currentDateTime || $date->format('D') == 'Sun') {
                $calendar .= '<td class="not-available"><span>'. $currentDay .'</span>';
            } elseif (in_array($date->format('Y-m-d'), $this->bookedDates)) {
                $calendar .= '<td class="not-available"><span>'. $currentDay .'</span>';
            } else {
                $calendar .= '<td'. $activeLink .'><div hx-get="/schedule-a-call/?year=' . $this->year . '&month=' . $this->month . '&date=' . $date->format('Y-m-d') .'" hx-push-url="/schedule-a-call/?date='. $date->format('Y-m-d') .'"  hx-target="#content-area" hx-select=".calendar"><span'. $todayIndicator .'>' . $currentDay .'</span></div>';
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
        echo $calendar;
  }

  private function bookedTimeslots() {
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

  public function buildTimeslot() {
    $bookedTimeslots = $this->bookedTimeslots();
    $openTime = $this->dateTime->format('Y-m-d') . ' 9:00:00';
    $closeTime = $this->dateTime->format('Y-m-d') . ' 17:00:00';

    $openTime = $this->timezoneConverter($openTime, $this->timezone, config('app.timezone_display'));
    $closeTime = $this->timezoneConverter($closeTime, $this->timezone, config('app.timezone_display'));

    $timeslots = array();
    for ($t = $openTime->timestamp; $t < $closeTime->timestamp; $t+=1800) {
        if ($this->dateTime->isToday()) {
            if ($t < $this->currentDateTime->timestamp) continue;
        }
        $timeslots[] = Carbon::createFromTimestamp($t, $this->timezone)->format('H:i:s');
    }

    $output = '<p><strong>'. $this->dateTime->format('D') .'</strong> '. $this->dateTime->format('j') .'</p>';
    $output .= '<ul>';
    foreach ($timeslots as $timeslot) {
        if (!empty($bookedTimeslots) && in_array($timeslot, $bookedTimeslots)) {
            $output .= '<li>'. date('g:i a', strtotime($timeslot)) .'</li>';
        } else {
            $dateTimeAvailable = new Carbon($this->dateTime->format('Y-m-d') . ' ' . $timeslot, $this->timezone);
            $output .= '<li><span hx-get="/schedule-a-call/introduction?date='. $this->dateTime->format('Y-m-d') .'&time='. $dateTimeAvailable->timestamp .'" hx-push-url="/schedule-a-call/introduction?date='. $this->dateTime->format('Y-m-d') .'&time='. $dateTimeAvailable->timestamp .'" hx-target="#content-area">'. date('g:i a', strtotime($timeslot)) .'</span></li>';
        }
    }
    $output .= '</ul>';
    if ($this->dateTime->isToday() && $this->currentDateTime->timestamp > $closeTime->timestamp) {
        $output .= 'Please consider rescheduling for tomorrow.';
    }

    echo $output;
  }
}
