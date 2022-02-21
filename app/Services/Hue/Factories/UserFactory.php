<?php

namespace App\Services\Hue\Factories;

use App\Services\Hue\DataObjects\User;

class UserFactory
{
    public static function make(array $attributes): User
    {
        return new User(
            name: data_get($attributes, 'username'),
        );
    }
}
