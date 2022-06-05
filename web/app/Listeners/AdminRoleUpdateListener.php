<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\Admin;


class AdminRoleUpdateListener
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
        $id = $event->user_id;
        $role = $event->role;

        Admin::query()->find($id)
            ->update([
                'role' => $role,
            ]);
    }
}
