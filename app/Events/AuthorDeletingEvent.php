<?php

namespace App\Events;

use App\Models\Author;
use Illuminate\Queue\SerializesModels;

class AuthorDeletingEvent
{
    use SerializesModels;

    public Author $author;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Author $author)
    {
        $this->author = $author;
    }
}
