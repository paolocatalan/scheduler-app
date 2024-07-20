<?php

namespace Tests\Feature;

// use Illuminate\Foundation\Testing\RefreshDatabase;

use App\Http\Helpers\Booker;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    public function test_calendar_returns_a_successfull_response(): void
    {
        $randomTimestamp = rand(1721395626, 1752931626);
        $dateTime = date('Y-m-d', $randomTimestamp);
        $response = $this->get('/schedule-a-call/?date='. $dateTime);
        $response->assertStatus(200);
    }

    public function test_booked_dates_returns_redirect_response(): void
    {
        $bookedDates = Booker::bookedDates();
        $response = $this->get('/schedule-a-call/?date='. $bookedDates[array_rand($bookedDates)]);
        $response->assertStatus(302);
    }

    public function test_timezone_reflecting_on_timeslots(): void
    {
        $randomTimestamp = rand(1721395626, 1752931626);
        $dateTime = date('Y-m-d', $randomTimestamp);
        $randomTimezone = timezone_identifiers_list()[array_rand(timezone_identifiers_list())];
        $response = $this->withCookies(['timezone' => $randomTimezone])->get('/schedule-a-call/?date='. $dateTime);
        $response->assertViewHas('timezone', $randomTimezone);
    }
}
