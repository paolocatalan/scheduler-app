<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Http\Helpers\Booker;
use Carbon\Carbon;

class CalendarTest extends TestCase
{
    public function test_date_checker_for_past_days()
    {
        $dateChecked = Booker::dateChecker('2023-08-18');
        $this->assertFalse($dateChecked->isPast());
    }

    public function test_date_checker_for_daysoff(): void
    {
        $dateChecked = Booker::dateChecker('2024-08-18'); // Sunday
        $this->assertFalse($dateChecked->dayOfWeek === Carbon::SUNDAY);
    }

    public function test_date_checker_for_booked_dates():void
    {
        $bookedDates = Booker::bookedDates();
        $dateChecked = Booker::dateChecker('2024-08-18');
        $this->assertFalse(in_array($dateChecked, $bookedDates));
    }

    public function test_booked_dates_showing_on_timeslots(): void
    {
        $this->post('/schedule-a-call', [
            'name' => 'DHH',
            'schedule_call' => '2024-08-20 10:00:00',
            'timezone' => 'Europe/Copenhagen',
            'email' => 'dhh@rails.com',
            'notes' => 'Ruby on Rails'
        ]);
        $calendar = new Booker('2024', '08', '2024-08-20', 'Europe/Kyiv');
        $reflector = new \ReflectionClass($calendar);
        $function = $reflector->getMethod('bookedTimeslots');
        $this->assertContains('10:00:00', $function->invoke($calendar));
    }

    public function test_timezone_changes_on_timeslots(): void
    {
        $calendar = new Booker('2024', '08', '2024-08-20', 'Europe/Kyiv');
        $this->assertSame(
            [
                '0' => '04:00:00',
                '1' => '04:30:00',
                '2' => '05:00:00',
                '3' => '05:30:00',
                '4' => '06:00:00',
                '5' => '06:30:00',
                '6' => '07:00:00',
                '7' => '07:30:00',
                '8' => '08:00:00',
                '9' => '08:30:00',
                '10' => '09:00:00',
                '11' => '09:30:00',
                '12' => '10:00:00',
                '13' => '10:30:00',
                '14' => '11:00:00',
                '15' => '11:30:00',
            ],
            $calendar->timeslots()
        );
    }

}
