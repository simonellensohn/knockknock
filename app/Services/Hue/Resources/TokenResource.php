<?php

namespace App\Services\Hue\Resources;

use App\Services\Hue\DataObjects\OAuthToken;
use App\Services\Hue\Exceptions\HueRequestException;
use App\Services\Hue\Factories\TokenFactory;
use App\Services\Hue\HueService;

class TokenResource
{
    public OAuthToken $token;

    public function __construct(
        private readonly HueService $service,
    ) {
    }

    public function service(): HueService
    {
        return $this->service;
    }

    public function get(string $code = null, bool $refresh = true): OAuthToken
    {
        $this->token ??= TokenFactory::make(readFromFile: true);

        if ($refresh) {
            if ($this->token->isEmpty()) {
                $this->token = $this->fetch($code);
            }

            if ($this->token->isExpired()) {
                $this->token = $this->refresh($this->token);
            }
        }

        return $this->token;
    }

    public function fetch(string $code = null): OAuthToken
    {
        $request = $this->service->makeRequest(withToken: false);

        $response = $request->asForm()
            ->withBasicAuth($this->service->clientId, $this->service->clientSecret)
            ->post($this->service->baseUrl.'/v2/oauth2/token', [
                'code' => $code,
                'grant_type' => 'authorization_code',
            ]);

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return TokenFactory::make((array) $response->json(), writeToFile: true);
    }

    public function refresh(OAuthToken $token): OAuthToken
    {
        $request = $this->service->makeRequest(withToken: false);

        $response = $request->asForm()
            ->withBasicAuth($this->service->clientId, $this->service->clientSecret)
            ->post($this->service->baseUrl.'/v2/oauth2/token', [
                'grant_type' => 'authorization_code',
                'refresh_token' => $token->refresh_token,
            ]);

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return TokenFactory::make((array) $response->json(), writeToFile: true);
    }
}
