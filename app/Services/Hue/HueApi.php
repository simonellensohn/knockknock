<?php

namespace App\Services\Hue;

use Illuminate\Http\Client\Response;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class HueApi
{
    protected string $baseUrl = 'https://api.meethue.com';

    public function __construct(public array $options = [])
    {}

    public function fetchAccessToken(string $code): string
    {
        $response = Http::asForm()
            ->withBasicAuth(config('services.hue.client_id'), config('services.hue.client_secret'))
            ->post($this->baseUrl.'/v2/oauth2/token', [
                'code' => $code,
                'grant_type' => 'authorization_code',
            ]);

        $tokens = $this->writeTokensToFile($response->json());

        return $tokens->access_token_expires_at;
    }

    public function createUser(): string
    {
        $this->send('/route/api/0/config', 'put', ['linkbutton' => true]);

        $response = $this->send('/route/api', 'post', [
            'devicetype' => config('services.hue.app_id'),
        ]);

        return $response->json('0.success.username');
    }

    public function getAccessToken(): string
    {
        $tokens = json_decode(Storage::disk('local')->get('hue.json') ?: '{}');

        if (! $tokens->access_token_expires_at || Carbon::parse($tokens->access_token_expires_at)->lt(Carbon::now())) {
            $response = Http::asForm()
                ->withBasicAuth(config('services.hue.client_id'), config('services.hue.client_secret'))
                ->post($this->baseUrl.'/v2/oauth2/token', ['grant_type' => 'refresh_token', 'refresh_token' => $tokens->refresh_token])
                ->throw();

            $tokens = $this->writeTokensToFile($response->json());
        }

        return $tokens->access_token;
    }

    /**
     * @return Collection<string, mixed>
     */
    public function fetchLights(): Collection
    {
        $response = $this->send('/route/api/'.config('services.hue.username').'/lights');

        return collect($response->json());
    }

    public function blinkAllLights(): bool
    {
        $response = $this->send('/route/api/'.config('services.hue.username').'/groups/8/action', 'put', [
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

    private function writeTokensToFile(array $tokens): object
    {
        $tokens['access_token_expires_at'] = Carbon::now()->addSeconds($tokens['expires_in'])->toDateTimeString();

        Storage::put('hue.json', json_encode($tokens, JSON_THROW_ON_ERROR));

        return (object) $tokens;
    }
}
