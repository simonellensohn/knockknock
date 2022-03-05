<?php

namespace App\Services\Hue;

use App\Services\Concerns\CanBeFaked;
use App\Services\Hue\Resources\ConfigurationResource;
use App\Services\Hue\Resources\LightResource;
use App\Services\Hue\Resources\TokenResource;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class HueService
{
    use CanBeFaked;

    public function __construct(
        public readonly string $baseUrl,
        public readonly string $appId,
        public readonly string $clientId,
        public readonly string $clientSecret,
        public readonly string $username,
        public readonly int $timeout = 10,
    ) {}

    public function makeRequest(bool $withToken = true): PendingRequest
    {
        $request = Http::baseUrl(
            $this->baseUrl.'/route/api/'.$this->username,
        )->timeout(
            $this->timeout,
        );

        if ($withToken) {
            $request->withToken(
                $this->token()->get()->access_token
            );
        }

        return $request;
    }

    public function configuration(): ConfigurationResource
    {
        return new ConfigurationResource($this);
    }

    public function light(): LightResource
    {
        return new LightResource($this);
    }

    public function token(): TokenResource
    {
        return new TokenResource($this);
    }
}
