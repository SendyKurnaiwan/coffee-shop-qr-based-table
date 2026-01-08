<?php

namespace App\Listeners;

use App\Models\User; // Add this import
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateMejaStatusOnLogin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event): void
    {
        /** @var User $user */
        $user = $event->user;

        if ($user->role === 'meja') {
            // Method 1: Using save() - clearer for Intelephense
            $user->is_occupied = true;
            $user->save();

            // OR Method 2: Using update() with proper type hint
            // $user->update([
            //     'is_occupied' => true,
            //     'last_login_at' => now()
            // ]);
        }
    }
}
