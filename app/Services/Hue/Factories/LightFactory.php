<?php

namespace App\Services\Hue\Factories;

use App\Services\Hue\DataObjects\Light;

class LightFactory
{
    public static function make(array $attributes): Light
    {
        return new Light(attributes: $attributes);
    }
}
