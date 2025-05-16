<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
// use Illuminate\Support\Facades\Gate; // Can be left commented
use Laravel\Passport\Passport; // <-- ENSURE THIS IMPORT IS PRESENT

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The model to policy mappings for the application.
     *
     * @var array<class-string, class-string>
     */
    protected $policies = [
        // We will add policies here later:
        // \App\Models\Venue::class => \App\Policies\VenuePolicy::class,
        // \App\Models\Event::class => \App\Policies\EventPolicy::class,
        // \App\Models\Ticket::class => \App\Policies\TicketPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        // Passport::routes(); // <-- ENSURE THIS LINE IS PRESENT AND UNCOMMENTED

        // Optional: Define token lifespans
        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
    }
}