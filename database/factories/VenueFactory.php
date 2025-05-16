<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Venue>
 */
class VenueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->company() . ' ' . $this->faker->randomElement(['Hall', 'Arena', 'Center', 'Theatre', 'Stadium']),
            'location' => $this->faker->address(),
            'capacity' => $this->faker->numberBetween(100, 10000), // Random capacity between 100 and 10,000
        ];
    }
}