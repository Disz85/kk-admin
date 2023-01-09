<?php

namespace App\Mail;

use App\Models\BrandChangeRequest;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BrandChangeRequestRejectionMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private BrandChangeRequest $brandChangeRequest;
    private User $user;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BrandChangeRequest $brandChangeRequest, User $user)
    {
        $this->brandChangeRequest = $brandChangeRequest;
        $this->user = $user;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(
            to:  [new Address($this->user->email, $this->user->lastname." ".$this->user->firstname)],
            subject: 'Márka '.($this->brandChangeRequest->brand ? 'módosítási' : 'feltöltési').' kérés elutasítva'
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {
        return new Content(
            view: 'emails.brandChangeRequests.rejection',
            with: [
                'brandChangeRequest' => $this->brandChangeRequest,
                'user' => $this->user,
            ]
        );
    }
}
