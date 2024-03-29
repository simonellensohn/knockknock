<?php

namespace App\Services\Hue\DataObjects;

use Illuminate\Support\Carbon;
use Spatie\LaravelData\Data;

class OAuthToken extends Data
{
    public function __construct(
        public string $name = '',
        public string $access_token = '',
        public string $access_token_expires_at = '',
        public int $expires_in = 0,
        public string $refresh_token = '',
        public string $token_type = '',
    ) {
    }

    public function setExpirationDate(): self
    {
        $this->access_token_expires_at = Carbon::now()
            ->addSeconds($this->expires_in)
            ->toDateTimeString();

        return $this;
    }

    public function isEmpty(): bool
    {
        return blank($this->access_token);
    }

    public function isExpired(): bool
    {
        if (blank($this->access_token_expires_at)) {
            return true;
        }

        return Carbon::parse($this->access_token_expires_at)->lt(Carbon::now());
    }
}
