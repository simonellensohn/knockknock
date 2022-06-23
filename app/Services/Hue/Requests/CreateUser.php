<?php

namespace App\Services\Hue\Requests;

final class CreateUser
{
    public function __construct(
        public readonly string $appId,
    ) {
    }

    public function toRequest(): array
    {
        return [
            'devicetype' => $this->appId,
        ];
    }
}
