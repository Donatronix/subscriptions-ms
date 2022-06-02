<?php

namespace App\Providers;

use App\Listeners\AdminRoleUpdateListener;
use App\Listeners\AdminUpdateListener;
use App\Listeners\NewAdminAddedListener;
use App\Listeners\NewUserRegisteredListener;
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
        'NewAdminAdded' => [
            NewAdminAddedListener::class,
        ],
        'AdminRoleUpdate' => [
            AdminRoleUpdateListener::class,
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
