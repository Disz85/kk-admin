<?php

namespace App\Listeners;

use App\Events\AuthorDeletingEvent;

class AuthorDeleting
{
    /**
     * Handle the AuthorDeletingEvent. Delete connected images.
     *
     * @param  \App\Events\AuthorDeletingEvent  $event
     * @return void
     */
    public function handle(AuthorDeletingEvent $event)
    {
        $event->author->image()->delete();
    }
}
