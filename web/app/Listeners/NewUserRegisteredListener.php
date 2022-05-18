<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\Subscriber;


class NewUserRegisteredListener
{


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


        Subscriber::query()->firstOrCreate([
            'user_id' => $id,
            'username' => $username,
        ]);

    }
}
