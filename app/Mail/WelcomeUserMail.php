<?php

namespace App\Mail;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class WelcomeUserMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public User $user,
        public string $plainPassword,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: Setting::get(
                'mail_subject_welcome',
                'Welkom bij de Proud Innovations offerte-applicatie'
            )
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'emails.welcome-user',
        );
    }
}
