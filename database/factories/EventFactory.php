<?php

namespace Database\Factories;

use App\Models\User;  // Import User model
use App\Models\Venue; // Import Venue model
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Event>
 */
class EventFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $startTime = $this->faker->dateTimeBetween('+1 week', '+3 months'); // Events in the future
        $durationHours = $this->faker->numberBetween(2, 5);
        // Clone start time to avoid modifying the original object when adding duration
        $endTime = (clone $startTime)->modify("+$durationHours hours");

        return [
            'title' => $this->faker->catchPhrase() . ' ' . $this->faker->bs(),
            'description' => $this->faker->paragraphs(3, true), // 3 paragraphs, as text
            'start_time' => $startTime,
            'end_time' => $endTime,
            'venue_id' => Venue::factory(), // Create a new Venue via its factory if not specified
            // Ensure the creator is an admin user.
            // If no admin user exists, create one.
            'user_id' => User::where('is_admin', true)->inRandomOrder()->first()?->id ?? User::factory()->create(['is_admin' => true])->id,
            'category' => $this->faker->randomElement(['Music', 'Sports', 'Theater', 'Conference', 'Workshop', 'Festival']),
            'price' => $this->faker->randomFloat(2, 10.00, 250.00), // Price between 10.00 and 250.00
        ];
    }
}