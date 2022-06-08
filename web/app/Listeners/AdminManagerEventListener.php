<?php

namespace App\Listeners;

use App\Events\NewUserRegistered;
use App\Models\Admin;


class AdminManagerEventListener
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
     * @param mixed $event
     *
     * @return void
     */
    public function handle(mixed $event)
    {
        $service = $event->service;
        if ($service !== config('api.microservice')) {
            return;
        }

        $user = $event->admin;
        $role = $event->role;
        $action = $event->role;
        if (is_array($user)) {
            $user = collect($user);
        }

        $id = $user->id;

        if ($action === 'store') {
            Admin::query()->firstOrCreate([
                'user_id' => $id,
                'name' => $user->name,
                'email' => $user->email,
                'phone' => $user->phone,
                'role' => $role,
            ]);
        }

        if ($action === 'update') {
            Admin::query()->find($id)
                ->update([
                    'role' => $role,
                ]);
        }
        
        if ($action === 'delete') {
            Admin::query()->find($id)
                ->delete();
        }

    }
}
