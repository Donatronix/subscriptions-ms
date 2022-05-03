<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\Subscriber;
use App\Traits\GetCountryTrait;

class NewUserRegisteredListener
{
    use GetCountryTrait;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param NewUserRegistered $event
     *
     * @return void
     */
    public function handle(mixed $event)
    {
        $user = $event->user;

        $username = $user->username;
        $id = $user->id;

        Subscriber::query()->create([
            'user_id' => $id,
            'username' => $username,
        ]);

    }
}
