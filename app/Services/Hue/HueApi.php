<?php

namespace App\Services\Hue;

use App\Services\Hue\DataObjects\OAuthToken;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HueApi
{
    public function __construct(
        public readonly string $baseUrl,
        public readonly string $appId,
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $username,
    ) {}

    public function fetchAccessToken(string $code): OAuthToken
    {
        $response = Http::asForm()
            ->withBasicAuth($this->clientId, $this->clientSecret)
            ->post($this->baseUrl.'/v2/oauth2/token', [
                'code' => $code,
                'grant_type' => 'authorization_code',
            ])
            ->throw();

        return $this->writeTokensToFile($response->json());
    }

    public function createUser(): string
    {
        $this->send('/route/api/0/config', 'put', ['linkbutton' => true]);

        $response = $this->send('/route/api', 'post', [
            'devicetype' => $this->appId,
        ]);

        return $response->json('0.success.username');
    }

    public function getAccessToken(): string
    {
        $token = new OAuthToken(json_decode(Storage::get('hue.json') ?: '{}', true));

        if ($token->isInvalid()) {
            $response = Http::asForm()
                ->withBasicAuth($this->clientId, $this->clientSecret)
                ->post($this->baseUrl.'/v2/oauth2/token', [
                    'grant_type' => 'refresh_token',
                    'refresh_token' => $token->refresh_token,
                ])
                ->throw();

            $token = $this->writeTokensToFile($response->json());
        }

        return $token->access_token;
    }

    public function fetchLights(): array
    {
        return $this
            ->send('/route/api/'.$this->username.'/lights')
            ->json();
    }

    public function blinkAllLights(): bool
    {
        $response = $this->send('/route/api/'.$this->username.'/groups/8/action', 'put', [
            'alert' => 'lselect',
        ]);

        return $response->successful();
    }

    private function send(string $url, string $method = 'get', array $data = []): Response
    {
        return Http::asJson()
            ->withToken($this->getAccessToken())
            ->baseUrl($this->baseUrl)
            ->{$method}($url, $data);
    }

    private function writeTokensToFile(array $response): OAuthToken
    {
        $token = new OAuthToken($response);
        $token->setExpirationDate();

        Storage::put('hue.json', json_encode($token->toArray(), JSON_THROW_ON_ERROR));

        return $token;
    }
}
