<?php

namespace App\Mail;

use App\Models\Quote;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class QuoteClientMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Quote $quote,
        public string $signUrl,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Offerte van Proud Innovations B.V. — '.$this->quote->quote_number,
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote-client',
        );
    }
}
