<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;
use Illuminate\Support\Facades\Auth;


class EstimateRequestMail extends Mailable
{
    use Queueable, SerializesModels;

    public $data;
    public $dynamicSubject;
    /**
     * Create a new message instance.
     *
     * @param array $data
     */
    public function __construct($data)
    {
        $this->data = $data;
        $this->dynamicSubject ='Nuova Richiesta Riparazione ' . now()->format('Y-m-d H:i:s') . ' ' . uniqid();
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            //from: new Address("pippo@topolinia.it", "Pippo"),
            subject: $this->dynamicSubject,  // The subject of the email
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.estimate-request',  // Reference to the custom Blade template
            with: ['data' => $this->data],    // Pass the form data to the view
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
