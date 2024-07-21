<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use App\Models\Booking;
use Tests\TestCase;

class ExampleTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    public function test_fail_with_message_if_dates_are_already_booked(): void
    {
        Booking::factory()->create([
            'schedule_call' => '2024-08-27 12:00:00',
        ]);

        $response = $this->post('/schedule-a-call', [
            'name' => 'Primagen',
            'schedule_call' => '2024-08-27 12:00:00',
            'timezone' => 'America/Los_Angeles',
            'email' => 'prime@netflix.com',
            'notes' => 'Go and HTMX'
        ]);
        $response->assertInvalid(['schedule_call']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
            'schedule_call' => 'Something went wrong. Please go back and select a date again.'
        ]);
    }

    public function test_fail_with_message_if_dates_are_missing(): void
    {
        $response = $this->post('/schedule-a-call', [
            'name' => 'Primagen',
            'email' => 'prime@netflix.com',
            'notes' => 'Go and HTMX'
        ]);
        $response->assertInvalid(['timezone']);
        $response->assertStatus(302);
        $response->assertSessionHasErrors([
                'timezone' => 'Something really went wrong. Please go back and select a timezone again.'
            ]);
    }

    public function test_store_booking(): void
    {
        $response = $this->post('/schedule-a-call', [
            'name' => 'Primagen',
            'schedule_call' => '2024-08-27 12:00:00',
            'timezone' => 'America/Los_Angeles',
            'email' => 'prime@netflix.com',
            'notes' => 'Go and HTMX'
        ]);
        $response->assertSuccessful();
        $this->assertDatabaseHas('bookings', [
            'name' => 'Primagen'
        ]);
        $this->assertDatabaseCount('bookings', 1);
    }
}
