<?php

namespace App\Http\Helpers;

use App\Models\Booking;

class Booker
{
    public static function dateChecker($date, $timezone_selected) {
        //check for past days
        $current_date_time = date('Y-m-d H:i:s');
        $current_date_time = new \DateTime($current_date_time, new \DateTimeZone($timezone_selected));
        $current_date_time = $current_date_time->format('Y-m-d H:i:s');

        if ($date . ' 23:59:59' < $current_date_time) {
            $date = date('Y-m-d', strtotime($current_date_time));
        }

        // check for days off, keep the to default timezone
        $ph_date = new \DateTime($date, new \DateTimeZone(date_default_timezone_get()));
        $ph_date_time = $ph_date->format('Y-m-d H:i:s');
        $day_text = date('D', strtotime($ph_date_time));
        if ($day_text == 'Sun') {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }

        // check for unavailable dates
        $booked_dates = array('2024-06-28', '2024-07-01', '2024-08-09');
        while (in_array($date, $booked_dates)) {
            $date = date('Y-m-d', strtotime($date . ' +1 day'));
        }

        return $date;

    }

    public static function timezoneConverter($time, $timezone_to, $timezone_from) {
        $date = new \DateTime($time, new \DateTimeZone($timezone_from));
        $date->setTimezone(new \DateTimeZone($timezone_to));
        $date = $date->format('Y-m-d H:i:s');
        return $date;

    }

    public static function buildCalendar($year, $month, $timezone_selected) {
        $booked_dates = array('2024-06-28', '2024-07-01', '2024-08-09');

        $current_date_time = date('Y-m-d H:i:s');
        $current_date_time = new \DateTime($current_date_time, new \DateTimeZone($timezone_selected));
        $current_date_time = $current_date_time->format('Y-m-d H:i:s');
        $current_date = date('Y-m-d', strtotime($current_date_time));
        $current_month = date('m', strtotime($current_date_time));

        $days_of_week = array('Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat');
        $first_day_of_month = mktime(0, 0, 0, $month, 1, $year);
        $number_days = date('t', $first_day_of_month);
        $date_components = getdate($first_day_of_month);
        $month_name = $date_components['month'];
        $day_of_week = $date_components['wday'];
        $month_numeric = $date_components['mon'];
        $current_day = 1;

        $disable_past_month = ($month_numeric == $current_month) ? ' disabled="disabled"' : '';
        $previous_month_navlink = Booker::dateChecker(date('Y-m-d', mktime(0, 0, 0, $month-1, 1, $year)), $timezone_selected);
        $next_month_navlink = Booker::dateChecker(date('Y-m-d', mktime(0, 0, 0, $month+1, 1, $year)), $timezone_selected);

        $calendar = '<div class="month-header"><h2>'. $month_name . ' '. $year . '</h2>';
        $calendar .= '<nav>';
        $calendar .= '<button hx-get="/schedule-a-call/?year=' . date('Y', mktime(0, 0, 0, $month-1, 1, $year)) . '&month=' . date('m', mktime(0, 0, 0, $month-1, 1, $year)) . '&date=' . $previous_month_navlink .'&timezone='. $timezone_selected .'" hx-push-url="/schedule-a-call/?date=' . $previous_month_navlink .'"'. $disable_past_month .' hx-target="body">&lsaquo;</button>';
        $calendar .= '<button hx-get="/schedule-a-call/?year=' . date('Y', mktime(0, 0, 0, $month+1, 1, $year)) . '&month=' . date('m', mktime(0, 0, 0, $month+1, 1, $year)) . '&date=' . $next_month_navlink .'&timezone='. $timezone_selected .'" hx-push-url="/schedule-a-call/?date=' . $next_month_navlink . '" hx-target="body">&rsaquo;</button>';
        $calendar .= '</nav></div>';

        $calendar .= '<table><tr>';
        foreach($days_of_week as $d) {
            $calendar .= '<th>' . $d . '</th>';
        }
        $calendar .= '</tr><tr>';

        if ($day_of_week > 0) { 
            for ($j=0; $j < $day_of_week; $j++) {
                $calendar .= '<td></td>'; 
            }
        }

        while ($current_day <= $number_days) {
            if ($day_of_week == 7) {
                $day_of_week = 0;
                $calendar .= '</tr><tr>';
            }

            $current_day_of_the_month = str_pad($current_day, 2, '0', STR_PAD_LEFT);
            $date = $year . '-' . $month . '-' . $current_day_of_the_month;
            $date_url = (isset($_GET['date'])) ? $_GET['date'] : $current_date;
            $active_navlink = ($date_url == $date) ? ' class="active"' : '';
            $today_indicator = ($current_date == $date) ? ' class="today"' : '';
            $day_text = date('D', strtotime($date));
            
            if ($date . ' 23:59:59' < $current_date_time || $day_text == 'Sun') {
                $calendar .= '<td class="not-available"><span>'. $current_day .'</span>';
            } elseif (in_array($date, $booked_dates)) {
                $calendar .= '<td class="not-available"><span>'. $current_day .'</span>';
            } else {
                $calendar .= '<td'. $active_navlink .'><div hx-get="/schedule-a-call/?year=' . date('Y', mktime(0, 0, 0, $month, $current_day, $year)) . '&month=' . date('m', mktime(0, 0, 0, $month, $current_day, $year)) . '&date=' . $date .'&timezone='. $timezone_selected .'" hx-push-url="/schedule-a-call/?date='. $date .'"  hx-target="body"><span'. $today_indicator .'>' . $current_day .'</span></div>';
            }

            $calendar .= '</td>';
            $current_day++;
            $day_of_week++;
        }

        if ($day_of_week != 7) {
            $remaining_days = 7 - $day_of_week;
            for ($i=0;  $i < $remaining_days; $i++) {
                $calendar .= '<td></td>'; 
            }

        }

        $calendar .= '</tr>';
        $calendar .= '</table>';
        echo $calendar;

  }

  public static function buildTimeslot($date, $timezone) {

    // get the booked timeslots
    $bookings = Booking::where('schedule_call', '>', date('Y-m-d H:i:s'))->get();
    foreach ($bookings as $booking) {
        $booked_dates = $booking->schedule_call;
        $booked_date = date('Y-m-d', strtotime($booked_dates));
        $booked_timeslots = array();
        if ($booked_date == $date) {
            $booked_timeslots = $booking->schedule_call;
            $timezone_to = $booking->timezone;
            $timezone_from = $timezone;
            $booked_date_converted = Booker::timezoneConverter($booked_timeslots, $timezone_to, $timezone_from);
            $booked_timeslot[] = substr($booked_date_converted, 11);
        } else {
            $booked_timeslot[] = '';
        }
    }

    $open_time = $date . ' 9:00:00';
    $close_time = $date . ' 17:00:00';
    $current_date_time = date('Y-m-d H:i:s');

    if ($timezone != date_default_timezone_get()) {
        $timezone_to = $timezone;
        $timezone_from = date_default_timezone_get();
        $open_time = Booker::timezoneConverter($open_time, $timezone_to, $timezone_from);
        $close_time = Booker::timezoneConverter($close_time, $timezone_to, $timezone_from);
        $current_date_time = Booker::timezoneConverter($current_date_time, $timezone_to, $timezone_from);
    }
    $open_time = strtotime($open_time);
    $close_time = strtotime($close_time);
    $current_date = date('Y-m-d', strtotime($current_date_time));
    $current_timestamp = strtotime($current_date_time);
    $timeslots = array();
    for ($t = $open_time; $t < $close_time; $t+=1800) {
        if ($date === $current_date) {
            if ($t < $current_timestamp) continue;
        }
        $timeslots[] = date('H:i:s', $t);
    }

    echo '<p><strong>'. date_format(date_create($date), 'D') .'</strong> '. date_format(date_create($date), 'j') .'</p>'; 
    echo '<ul>';
    foreach ($timeslots as $timeslot) {
        if ( !empty($booked_timeslot) && in_array($timeslot, $booked_timeslot)) {
            echo '<li>'. date('g:i a', strtotime($timeslot)) .'</li>';
        } else {
            $timestamp = strtotime($date . ' ' . $timeslot);
            echo '<li><a href="/schedule-a-call/introduction?date='. $date .'&time='. $timestamp .'&timezone='. $timezone .'" hx-get="/schedule-a-call/introduction?date='. $date .'&time='. $timestamp .'&timezone='. $timezone .'" hx-push-url="true" hx-target="body">'. date('g:i a', strtotime($timeslot)) .'</a></li>';
        }
    }
    echo '</ul>';
    if ($date == $current_date && $current_timestamp > $close_time) {
        echo 'Please consider rescheduling for tomorrow.';
    }

  }
}
