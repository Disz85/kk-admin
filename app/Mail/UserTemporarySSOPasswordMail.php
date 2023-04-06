<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class UserTemporarySSOPasswordMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private User $user;
    private string $password;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, string $password)
    {
        $this->user = $user;
        $this->password = $password;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            to:  [new Address($this->user->email, $this->user->lastname." ".$this->user->firstname)],
            subject: 'Ideiglenes jelszó',
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.temp-user-password',
            with: [
                'user' => $this->user,
                'password' => $this->password,
            ]
        );
    }
}
