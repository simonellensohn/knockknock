<?php

namespace App\Services\Hue;

use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;

class HueApi
{
    protected PendingRequest $request;

    public function __construct(public array $options = [])
    {
        $this->request = Http::withOptions(['verify' => false])
            ->withHeaders(['hue-application-key' => $options['applicationKey']])
            ->baseUrl($options['baseUrl']);
    }

    public function fetchRooms(): Collection
    {
        $rooms = $this->request->get('/resource/room');

        return collect($rooms->json('data'));
    }
}
