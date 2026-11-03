<?php

namespace App\Mail;

use Carbon\CarbonInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class StaffInvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $invitationCode,
        public string $role,
        public CarbonInterface $expiresAt,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Campus Resit Portal staff invitation',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.staff-invitation',
            with: [
                'roleTitle' => $this->role === 'admin' ? 'System Administrator' : 'Chief Examiner',
                'invitationUrl' => route('staff.invitation'),
                'expiryText' => $this->expiresAt->format('F j, Y \\a\\t g:i A'),
            ],
        );
    }
}
