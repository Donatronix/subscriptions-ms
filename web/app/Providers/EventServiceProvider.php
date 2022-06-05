<?php

namespace App\Providers;

use App\Events\NewUserRegistered;
use App\Jobs\subscriberJobs;
use App\Listeners\NewUserRegisteredListener;
use App\Listeners\WaitingListMS;
use Laravel\Lumen\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'NewUserRegistered' => [
            NewUserRegisteredListener::class,
        ],
        'WaitingList' => [
            WaitingListMS::class,
            ],
    ];

    /**
     * Determine if events and listeners should be automatically discovered.
     *
     * @return bool
     */
    public function shouldDiscoverEvents(): bool
    {
        return true;
    }
}
