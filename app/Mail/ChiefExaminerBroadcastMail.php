<?php

namespace App\Mail;

use App\Models\BroadcastMessage;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ChiefExaminerBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public BroadcastMessage $broadcast,
        public User $student,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'USTED Examination Notice: ' . $this->broadcast->title,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.broadcast-announcement',
            with: [
                'broadcast' => $this->broadcast,
                'student' => $this->student,
            ],
        );
    }
}
