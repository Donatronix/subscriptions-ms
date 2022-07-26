<?php

namespace App\Listeners;

use App\Models\Subscriber;

class NewUserRegisteredListener
{
    /**
     * Handle the event.
     *
     * @param mixed $event
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
