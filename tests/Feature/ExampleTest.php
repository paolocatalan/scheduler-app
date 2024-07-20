<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Helpers\Booker;
use Carbon\Carbon;
use ReflectionClass;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    function randomDate()
    {
        $randomTimestamp = rand(1721395626, 1752931626);
        $dateTime = Booker::dateChecker(date('Y-m-d', $randomTimestamp));
        $randomTimezone = timezone_identifiers_list()[array_rand(timezone_identifiers_list())];
        $dateTime->shiftTimezone($randomTimezone);
        return $dateTime;
    }

    public function test_calendar_returns_a_successfull_response(): void
    {
        $dateTime = $this->randomDate();
        $response = $this->get('/schedule-a-call/?date='. $dateTime->format('Y-m-d'));
        $response->assertStatus(200);
    }

    public function test_booked_dates_returns_redirect_response(): void
    {
        $bookedDates = Booker::bookedDates();
        $response = $this->get('/schedule-a-call/?date=' . $bookedDates[array_rand($bookedDates)]);
        $response->assertStatus(302);
    }

    public function test_date_picker(): void
    {
        $dateTime = $this->randomDate();
        $response = $this->get('/schedule-a-call/?date=' . $dateTime->format('Y-m-d'));
        $response->assertSeeText($dateTime->format('F'), true);
    }

    public function test_timeslot_updating_on_timezone_changes()
    {
        $dateTime = $this->randomDate();
        $openTime = Booker::timezoneConverter($dateTime->format('Y-m-d') . ' 9:00:00', $dateTime->tzName, config('app.timezone_display'));
        
        $calendar = new Booker($dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('Y-m-d'), $dateTime->tzName);
        // get a private method
        $reflector = new ReflectionClass($calendar);
        $function = $reflector->getMethod('timeslots');

        $this->assertIsArray($function->invoke($calendar));
        $this->assertContains($openTime->format('H:i:s'), $function->invoke($calendar));

    }
}
