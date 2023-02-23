<?php

namespace App\Observers;

use App\Models\Author;

class AuthorObserver
{
    /**
     * Handle the Author "deleting" event.
     *
     * @param Author $author
     * @return void
     */
    public function deleting(Author $author): void
    {
        $author->image()->delete();
    }
}
