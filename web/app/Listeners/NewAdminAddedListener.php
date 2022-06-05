<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\Admin;


class NewAdminAddedListener
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
        $user = $event->admin;
        $role = $event->role;
        if (is_array($user)) {
            $user = collect($user);
        }

        $id = $user->id;


        Admin::query()->firstOrCreate([
            'user_id' => $id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'role' => $role,
        ]);

    }
}
