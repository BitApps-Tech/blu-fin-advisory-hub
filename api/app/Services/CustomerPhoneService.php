<?php

namespace App\Services;

use App\Models\Customer;

class CustomerPhoneService
{
    public const ETHIOPIAN_PHONE_REGEX = '/^(\+?251|0)?[79]\d{8}$/';

    /**
     * Normalize any Ethiopian mobile input to +251XXXXXXXXX.
     */
    public function normalize(?string $phone): ?string
    {
        if ($phone === null || trim($phone) === '') {
            return null;
        }

        $compact = preg_replace('/\s+/', '', $phone);
        $digits = ltrim($compact, '+');

        if (str_starts_with($digits, '251')) {
            $national = substr($digits, 3);
        } else {
            $national = ltrim($digits, '0');
        }

        if ($national === '' || !preg_match('/^[79]\d{8}$/', $national)) {
            return null;
        }

        return '+251' . $national;
    }

    public function isValid(?string $phone): bool
    {
        if ($phone === null || trim($phone) === '') {
            return false;
        }

        $compact = preg_replace('/\s+/', '', $phone);

        return (bool) preg_match(self::ETHIOPIAN_PHONE_REGEX, $compact);
    }

    /**
     * Create a customer only when the phone is not already in the table.
     */
    public function recordIfNew(?string $phone, ?string $name = null): ?Customer
    {
        $normalized = $this->normalize($phone);
        if (!$normalized) {
            return null;
        }

        $existing = Customer::where('phone', $normalized)->first();
        if ($existing) {
            return $existing;
        }

        return Customer::create([
            'phone' => $normalized,
            'name' => $name !== null && trim($name) !== '' ? trim($name) : null,
        ]);
    }

    public function wasNewlyCreated(?Customer $customer): bool
    {
        return $customer !== null && $customer->wasRecentlyCreated;
    }
}
