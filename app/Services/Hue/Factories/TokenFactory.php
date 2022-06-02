<?php

namespace App\Services\Hue\Factories;

use App\Services\Hue\DataObjects\OAuthToken;
use Illuminate\Support\Facades\Storage;

class TokenFactory
{
    public static function make(array $attributes = [], bool $writeToFile = false, bool $readFromFile = false): OAuthToken
    {
        if ($readFromFile) {
            $attributes = json_decode(
                json: Storage::disk('local')->get('hue.json') ?: '{}',
                associative: true,
                flags: JSON_THROW_ON_ERROR
            );
        }

        $token = new OAuthToken(array_filter([
            'name' => data_get($attributes, 'username'),
            'access_token' => data_get($attributes, 'access_token'),
            'access_token_expires_at' => data_get($attributes, 'access_token_expires_at'),
            'expires_in' => data_get($attributes, 'expires_in'),
            'refresh_token' => data_get($attributes, 'refresh_token'),
            'token_type' => data_get($attributes, 'token_type'),
        ]));

        if ($token->expires_in > 0 && blank($token->access_token_expires_at)) {
            $token->setExpirationDate();
        }

        if ($writeToFile) {
            Storage::put('hue.json', json_encode($token->toArray(), JSON_THROW_ON_ERROR));
        }

        return $token;
    }
}
