<?php

namespace App\Services;

use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\View;
use Mailtrap\Config;
use Mailtrap\EmailHeader\CategoryHeader;
use Mailtrap\Helper\ResponseHelper;
use Mailtrap\MailtrapClient;
use Psr\Http\Message\ResponseInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Mime\Email;

class MailtrapEmailService
{
    private const NEWSLETTER_LOGO_CID = 'mamokacha-logo';

    public function isConfigured(): bool
    {
        return !empty($this->apiKey());
    }

    /**
     * Send a Symfony Mime email via Mailtrap Email API.
     */
    public function send(Email $email, bool $bulk = false): array
    {
        $client = $this->client($bulk);
        $layer = $bulk ? 'bulkSending' : 'sending';

        /** @var ResponseInterface $response */
        $response = $client->{$layer}()->emails()->send($email);

        return ResponseHelper::toArray($response);
    }

    /**
     * Render and send a Laravel Mailable through Mailtrap.
     */
    public function sendMailable(
        string $to,
        Mailable $mailable,
        ?string $toName = null,
        bool $bulk = false,
        ?string $category = null
    ): array {
        $logoPath = $this->newsletterLogoPath();
        $rendered = $this->renderMailable(
            $mailable,
            $logoPath ? ['newsletterLogoCid' => self::NEWSLETTER_LOGO_CID] : []
        );

        $email = (new Email())
            ->from($this->fromAddress())
            ->to(new Address($to, $toName ?? ''))
            ->subject($rendered['subject'])
            ->html($rendered['html']);

        if ($logoPath) {
            $email->embedFromPath($logoPath, self::NEWSLETTER_LOGO_CID, 'image/png');
        }

        if (!empty($rendered['text'])) {
            $email->text($rendered['text']);
        }

        if ($category) {
            $email->getHeaders()->add(new CategoryHeader($category));
        }

        return $this->send($email, $bulk);
    }

    /**
     * Send the integration test email used by `php artisan send-mail`.
     */
    public function sendTestEmail(?string $to = null): array
    {
        $recipient = $to ?: $this->testRecipient();

        if (!$recipient) {
            throw new \RuntimeException(
                'Set MAILTRAP_TEST_TO in .env or pass --to=recipient@example.com.'
            );
        }

        $email = (new Email())
            ->from($this->fromAddress())
            ->to(new Address($recipient))
            ->subject('You are awesome!')
            ->text('Congrats for sending test email with Mailtrap!');

        $email->getHeaders()->add(new CategoryHeader('Integration Test'));

        return $this->send($email, $this->useBulkByDefault());
    }

    private function client(bool $bulk = false): MailtrapClient
    {
        $config = new Config($this->apiKey());

        if ($bulk) {
            $config->setHost($this->bulkHost());
        } elseif ($host = $this->host()) {
            $config->setHost($host);
        }

        return new MailtrapClient($config);
    }

    private function renderMailable(Mailable $mailable, array $extraViewData = []): array
    {
        $mailable->build();

        $viewData = array_merge(
            method_exists($mailable, 'buildViewData') ? $mailable->buildViewData() : [],
            $mailable->viewData ?? [],
            $extraViewData
        );

        $html = View::make($mailable->view, $viewData)->render();
        $text = trim(preg_replace('/\s+/', ' ', strip_tags(preg_replace('/<br\s*\/?>/i', "\n", $html))));

        return [
            'subject' => $mailable->subject ?? config('app.name'),
            'html' => $html,
            'text' => $text,
        ];
    }

    private function newsletterLogoPath(): ?string
    {
        $path = resource_path('images/mamokacha-logo.png');

        return is_file($path) ? $path : null;
    }

    private function fromAddress(): Address
    {
        return new Address(
            config('mail.from.address', 'hello@example.com'),
            config('mail.from.name', config('app.name'))
        );
    }

    private function apiKey(): ?string
    {
        $key = config('services.mailtrap.api_key');

        return is_string($key) && $key !== '' ? $key : null;
    }

    private function host(): ?string
    {
        return config('services.mailtrap.host');
    }

    private function bulkHost(): string
    {
        return config('services.mailtrap.bulk_host', 'bulk.api.mailtrap.io');
    }

    private function testRecipient(): ?string
    {
        $recipient = config('services.mailtrap.test_to');

        return is_string($recipient) && $recipient !== '' ? $recipient : null;
    }

    private function useBulkByDefault(): bool
    {
        return (bool) config('services.mailtrap.use_bulk', false);
    }
}
