<?php

namespace App\Providers;

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
            'App\Listeners\NewUserRegisteredListener',
        ],
        'AdminManagerEvent' => [
            'App\Listeners\AdminManagerEventListener',
        ],
        'Public' => [
            'App\Listeners\WaitingListMSListener',
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
