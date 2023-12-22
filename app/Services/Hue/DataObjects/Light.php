<?php

namespace App\Services\Hue\DataObjects;

use Spatie\LaravelData\Data;

class Light extends Data
{
    public function __construct(
        public array $attributes
    ) {
    }
}
