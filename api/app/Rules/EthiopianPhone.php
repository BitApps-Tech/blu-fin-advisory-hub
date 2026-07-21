<?php

namespace App\Rules;

use App\Services\CustomerPhoneService;
use Illuminate\Contracts\Validation\Rule;

class EthiopianPhone implements Rule
{
    public function passes($attribute, $value): bool
    {
        return app(CustomerPhoneService::class)->isValid((string) $value);
    }

    public function message(): string
    {
        return 'Phone number must be a valid Ethiopian mobile number (e.g. +251912345678 or 0912345678).';
    }
}
