<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;   // Import User model
use App\Models\Venue;  // Import Venue model
use App\Models\Event;  // Import Event model
use App\Models\Ticket; // Import Ticket model
use Illuminate\Support\Facades\Hash; // Import Hash facade

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // 1. Create an Admin User (if one doesn't already exist with this email)
        $admin = User::firstOrCreate(
            ['email' => 'admin@example.com'], // Find by email
            [ // Create with these attributes if not found
                'name' => 'Admin User',
                'password' => Hash::make('password'), // Use a strong password in real apps!
                'is_admin' => true,
                'email_verified_at' => now(), // Optional: Mark as verified
            ]
        );
        $this->command->info('Admin user created/ensured: admin@example.com / password');

        // 2. Create some regular users
        User::factory(10)->create(['is_admin' => false]); // Ensure these are not admins
        $this->command->info('10 Regular users created.');

        // 3. Create Venues and Events
        Venue::factory(5) // Create 5 venues
            ->has(
                Event::factory() // For each venue, create a random number of events (between 2 and 5)
                    ->count(rand(2, 5))
                    ->state(function (array $attributes, Venue $venue) use ($admin) {
                        // Ensure events are created by the admin user
                        // And associate the event with its parent venue
                        return ['user_id' => $admin->id, 'venue_id' => $venue->id];
                    })
                    ->afterCreating(function (Event $event) {
                        // 4. For each created Event, create some Tickets
                        // Get a random selection of non-admin users to book tickets
                        // Ensure we don't try to sell more tickets than venue capacity (e.g., max 1/4 capacity for seeding)
                        $maxTicketsToSeed = floor($event->venue->capacity / 4);
                        if ($maxTicketsToSeed < 1) $maxTicketsToSeed = 1; // ensure at least one if capacity is very low

                        $usersToBookTickets = User::where('is_admin', false)
                                                ->inRandomOrder()
                                                ->take(rand(1, min(5, $maxTicketsToSeed))) // Book between 1 and 5 tickets (or $maxTicketsToSeed)
                                                ->get();

                        foreach ($usersToBookTickets as $user) {
                            Ticket::factory()->create([
                                'user_id' => $user->id,
                                'event_id' => $event->id,
                                'price' => $event->price, // Use the event's base price for the ticket
                            ]);
                        }
                    })
            )
            ->create(); // This executes the creation of venues and their related events/tickets

        $this->command->info('5 Venues with associated Events and Tickets created.');
    }
}