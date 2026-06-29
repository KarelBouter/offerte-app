<?php

namespace App\Mail;

use App\Models\Quote;
use App\Models\Setting;
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
        $subjectTemplate = Setting::get(
            'mail_subject_quote',
            'Offerte van Proud Innovations B.V. — {quote_number}'
        );
        $subject = str_replace('{quote_number}', $this->quote->quote_number, $subjectTemplate);

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.quote-client',
        );
    }
}
