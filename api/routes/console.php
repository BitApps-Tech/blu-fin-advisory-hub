<?php

use App\Services\MailtrapEmailService;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('send-mail {--to= : Recipient email address}', function () {
    $service = app(MailtrapEmailService::class);

    if (!$service->isConfigured()) {
        $this->error('MAILTRAP_API_KEY is not configured in .env.');

        return 1;
    }

    try {
        $response = $service->sendTestEmail($this->option('to') ?: null);
        $this->info('Test email sent via Mailtrap Email API.');
        $this->line(json_encode($response, JSON_PRETTY_PRINT));
        $this->comment('View delivery logs at https://mailtrap.io/sending/email_logs');

        return 0;
    } catch (\Throwable $e) {
        $this->error($e->getMessage());

        return 1;
    }
})->purpose('Send a Mailtrap integration test email');
