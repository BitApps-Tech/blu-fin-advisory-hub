<?php

namespace App\Services;

use App\Models\NewsletterSubscriber;

class NewsletterEmailService
{
    public function normalize(?string $email): ?string
    {
        if ($email === null) {
            return null;
        }

        $normalized = strtolower(trim($email));

        if ($normalized === '' || !filter_var($normalized, FILTER_VALIDATE_EMAIL)) {
            return null;
        }

        return $normalized;
    }

    public function isValid(?string $email): bool
    {
        return $this->normalize($email) !== null;
    }

    /**
     * Add subscriber only when email is not already in the newsletter table.
     */
    public function recordIfNew(?string $email, ?string $name = null, ?string $source = null): ?NewsletterSubscriber
    {
        $normalized = $this->normalize($email);
        if (!$normalized) {
            return null;
        }

        $existing = NewsletterSubscriber::where('email', $normalized)->first();
        if ($existing) {
            return $existing;
        }

        return NewsletterSubscriber::create([
            'email' => $normalized,
            'name' => $name !== null && trim($name) !== '' ? trim($name) : null,
            'source' => $source ?? 'website',
            'subscribed_at' => now(),
        ]);
    }

    public function wasNewlyCreated(?NewsletterSubscriber $subscriber): bool
    {
        return $subscriber !== null && $subscriber->wasRecentlyCreated;
    }
}
