<?php

namespace App\Observers;

use App\Models\User;

class UserObserver
{
    /**
     * Handle the User "deleting" event.
     *
     * @param User $user
     * @return void
     */
    public function deleting(User $user)
    {
        $user->image()->detach();
    }
}
