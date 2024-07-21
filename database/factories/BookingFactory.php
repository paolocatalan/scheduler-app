<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Booking>
 */
class BookingFactory extends Factory
{
    
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    public function definition(): array
    {
        return [
            'name' => $this->faker->name(),
            'schedule_call' => $this->faker->dateTimeBetween('+1 days', '+30 days'),
            'timezone' => $this->faker->timezone(),
            'email' => $this->faker->safeEmail(),
            'notes' => $this->faker->sentence()
        ];
    }
}
