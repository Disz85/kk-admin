<?php

namespace App\Mail;

use App\Models\ProductChangeRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ProductChangeRequestRejectionMail extends Mailable
{
    use Queueable;
    use SerializesModels;

    private ProductChangeRequest $productChangeRequest;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(ProductChangeRequest $productChangeRequest)
    {
        $this->productChangeRequest = $productChangeRequest;
    }

    /**
     * Get the message envelope.
     *
     * @return Envelope
     */
    public function envelope()
    {
        return new Envelope(
            to:  [new Address($this->productChangeRequest->user->email, $this->productChangeRequest->user->lastname." ".$this->productChangeRequest->user->firstname)],
            subject: 'Termék '.($this->productChangeRequest->product ? 'módosítási' : 'feltöltési').' kérés elutasítva'
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
            view: 'emails.productChangeRequests.rejection',
            with: [
                'productChangeRequest' => $this->productChangeRequest,
                'user' => $this->productChangeRequest->user,
            ]
        );
    }
}
