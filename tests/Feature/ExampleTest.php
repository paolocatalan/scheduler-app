<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Http\Helpers\Booker;
use App\Models\Booking;
use ReflectionClass;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    function randomDate()
    {
        $randomTimestamp = rand(1722384000, 1725062400);
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

    public function test_booking_timeslot_updating_on_timezone_changes(): void
    {
        $dateTime = $this->randomDate();
        $openTime = Booker::timezoneConverter($dateTime->format('Y-m-d') . ' 9:00:00', $dateTime->tzName, config('app.timezone_display'));
        $closeTime = Booker::timezoneConverter($dateTime->format('Y-m-d') . ' 17:00:00', $dateTime->tzName, config('app.timezone_display'));
        for ($t = $openTime->timestamp; $t < $closeTime->timestamp; $t+=1800) {
            $timeslots[] = \Carbon\Carbon::createFromTimestamp($t, $dateTime->tzName)->format('H:i:s');
        }

        $calendar = new Booker($dateTime->format('Y'), $dateTime->format('m'), $dateTime->format('Y-m-d'), $dateTime->tzName);
        $reflector = new ReflectionClass($calendar);
        $function = $reflector->getMethod('timeslots');

        $this->assertSame($timeslots, $function->invoke($calendar));

    }

    public function test_booking_database(): void
    {
        $booking = Booking::factory()->create();
        $this->assertModelExists($booking);
    }

    public function test_store_booking(): void
    {
        $response = $this->post('/schedule-a-call', [
            'name' => 'Elon Musk',
            'schedule_call' => '2024-08-20 10:00:00',
            'timezone' => 'Europe/Kyiv',
            'email' => 'elon@twitter.com',
            'notes' => 'Need help on setting up PHP Unit testing'
        ]);

        $response->assertSuccessful();

        $this->assertDatabaseHas('bookings', [
            'name' => 'Elon Musk'
        ]);

        $this->assertDatabaseCount('bookings', 1);
    }
}
