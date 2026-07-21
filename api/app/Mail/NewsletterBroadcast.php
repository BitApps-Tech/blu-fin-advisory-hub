<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class NewsletterBroadcast extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public string $emailSubject,
        public string $headline,
        public string $previewText,
        public string $body,
        public ?string $recipientName = null,
    ) {
    }

    public function build()
    {
        return $this->subject($this->emailSubject)
            ->view('emails.newsletter.broadcast')
            ->with([
                'headline' => $this->headline,
                'previewText' => $this->previewText,
                'bodyHtml' => nl2br(e($this->body)),
                'recipientName' => $this->recipientName,
                'tagline' => 'Rooted in Ethiopian coffee heritage',
                'websiteUrl' => config('app.frontend_url', config('app.url')),
            ]);
    }
}
