<?php

namespace App\Services\Hue\DataObjects;

use Spatie\LaravelData\Data;

class User extends Data
{
    public function __construct(
        public string $name
    ) {
    }
}
