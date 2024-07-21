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

    function __construct($year, $month, $date, $timezone) {
        $this->year = $year;
        $this->month = $month;
        $this->date = $date;
        $this->timezone = $timezone;
        $this->dateTime = new CarbonImmutable($date, $timezone);
        $this->currentDateTime = CarbonImmutable::now(config('app.timezone_display'));
    }

    public static function dateChecker($date) {
        $dateTime = new Carbon($date, config('app.timezone_display'));
        $currentDateTime = Carbon::now(config('app.timezone_display'));
        //check for past days
        if ($currentDateTime > $dateTime) {
            $dateTime = $currentDateTime;
        }

        $bookedDates = self::bookedDates();

        $isUnavailableOrDayOff = function ($dateTime) use ($bookedDates) {
            return in_array($dateTime->format('Y-m-d'), $bookedDates) || $dateTime->dayOfWeek == Carbon::SUNDAY;
        };

        while ($isUnavailableOrDayOff($dateTime)) {
            $dateTime->addDay();
        }
        
        return $dateTime;
    }

    public static function timezoneConverter($dateTime, $timezoneTo, $timezoneFrom) {
        $dateTimeConverted = Carbon::createFromFormat('Y-m-d H:i:s', $dateTime, $timezoneFrom)->setTimezone($timezoneTo);
        return $dateTimeConverted;
    }

    public function buildCalendar() {
        $bookedDates = self::bookedDates();

        $daysOfWeek = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $firstDayOfMonth = mktime(0, 0, 0, $this->month, 1, $this->year);
        $dateComponents = getdate($firstDayOfMonth);
        $dayOfWeek = $dateComponents['wday'];
        $currentDay = 1;

        $disablePastMonth = ($this->dateTime->month == $this->currentDateTime->month) ? ' disabled="disabled"' : '';
        $previousMonthLink = self::dateChecker(date('Y-m-d', mktime(0, 0, 0, $this->month-1, 1, $this->year)));
        $nextMonthLink = self::dateChecker(date('Y-m-d', mktime(0, 0, 0, $this->month+1, 1, $this->year)));
    
        $calendar = '<div class="month-header"><h2>'. $this->dateTime->monthName . ' '. $this->dateTime->year . '</h2>';
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

        while ($currentDay <= $this->dateTime->daysInMonth) {
            if ($dayOfWeek == 7) {
                $dayOfWeek = 0;
                $calendar .= '</tr><tr>';
            }

            $date = Carbon::createFromDate($this->year, $this->month, $currentDay, config('app.timezone_display'));
            $dateRequest = (isset($_GET['date'])) ? $_GET['date'] : '';
            $activeLink = ($dateRequest == $date->format('Y-m-d')) ? ' class="active"' : '';
            $todayIndicator = ($this->currentDateTime->format('Y-m-d') == $date->format('Y-m-d')) ? ' class="today"' : '';

            if ($date < $this->currentDateTime || $date->format('D') == 'Sun') {
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
        echo $calendar;
  }

  private function bookedTimeslots()
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

  public function timeslots()
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

  public function buildTimeslot()
  {
    $bookedTimeslots = $this->bookedTimeslots();
    $output = '<p><strong>'. $this->dateTime->format('D') .'</strong> '. $this->dateTime->format('j') .'</p>';
    $output .= '<ul>';
    foreach ($this->timeslots() as $timeslot) {
        if (!empty($bookedTimeslots) && in_array($timeslot, $bookedTimeslots)) {
            $output .= '<li>'. date('g:i a', strtotime($timeslot)) .'</li>';
        } else {
            $dateTimeAvailable = new Carbon($this->dateTime->format('Y-m-d') . ' ' . $timeslot, $this->timezone);
            $output .= '<li><a hx-get="/schedule-a-call/introduction?date='. $this->dateTime->format('Y-m-d') .'&time='. $dateTimeAvailable->timestamp .'&timezone='. $this->timezone .'" hx-push-url="true" hx-target="#content-area" hx-select=".bookers-details">'. date('g:i a', strtotime($timeslot)) .'</a></li>';
        }
    }
    $output .= '</ul>';

    if ($this->dateTime->isToday() && empty($this->timeslots())) {
        $output .= '<p>Please consider rescheduling for tomorrow.</p>';
    }

    echo $output;
  }

  public static function bookedDates()
  {
    return array(
        '2024-08-01',
        '2024-08-02',
        '2024-08-03',
        '2024-08-05',
        '2024-08-06',
        '2024-08-07',
        '2024-08-08',
        '2024-08-09',
        '2024-08-10'
    );
  }
}
