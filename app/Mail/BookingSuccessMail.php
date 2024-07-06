<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $name, $date, $meetLink;

    /**
     * Create a new message instance.
     */
    public function __construct($name, $date, $meetLink)
    {
        $this->name = $name;
        $this->date = $date;
        $this->meetLink = $meetLink;
    }

    /**
     * Get the message envelope.    
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Meeting confirmation: ' . date('l, F j, Y, g:i a', strtotime($this->date)),
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.booking-success',
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
