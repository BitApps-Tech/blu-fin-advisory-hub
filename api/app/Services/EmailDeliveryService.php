<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailDeliveryService
{
    private MailtrapEmailService $mailtrap;

    public function __construct(MailtrapEmailService $mailtrap)
    {
        $this->mailtrap = $mailtrap;
    }

    /**
     * Send email via Mailtrap Email API when configured, otherwise Laravel Mail.
     */
    public function send(
        string $to,
        Mailable $mailable,
        ?string $toName = null,
        bool $bulk = false,
        ?string $category = null
    ): void {
        if ($this->mailtrap->isConfigured()) {
            $this->mailtrap->sendMailable($to, $mailable, $toName, $bulk, $category);

            return;
        }

        Mail::to($to)->send($mailable);
    }

    public function sendOrLog(
        string $to,
        Mailable $mailable,
        ?string $toName = null,
        bool $bulk = false,
        ?string $category = null,
        string $logContext = 'Email delivery failed'
    ): bool {
        try {
            $this->send($to, $mailable, $toName, $bulk, $category);

            return true;
        } catch (\Throwable $e) {
            Log::error($logContext, [
                'email' => $to,
                'error' => $e->getMessage(),
            ]);

            return false;
        }
    }
}
