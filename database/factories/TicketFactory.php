<?php

namespace Database\Factories;

use App\Models\Event; // Import Event model
use App\Models\User;  // Import User model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ticket>
 */
class TicketFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Get a random event, or create one if none exist
        $event = Event::inRandomOrder()->first() ?? Event::factory()->create();

        // Get a random non-admin user, or create one if none exist
        $user = User::where('is_admin', false)->inRandomOrder()->first() ?? User::factory()->create(['is_admin' => false]);

        return [
            'user_id' => $user->id,
            'event_id' => $event->id,
            'price' => $event->price, // Default to the event's base price
            'seat_info' => 'GA-' . $this->faker->unique()->randomNumber(4), // Unique General Admission seat number
            'booking_time' => $this->faker->dateTimeThisYear('-1 month'), // Booking time within the last month of this year
        ];
    }
}