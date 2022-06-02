<?php

namespace App\Services\Hue\Resources;

use App\Services\Hue\DataObjects\Light;
use App\Services\Hue\Exceptions\HueRequestException;
use App\Services\Hue\Factories\LightFactory;
use App\Services\Hue\HueService;
use Illuminate\Support\Collection;

class LightResource
{
    public function __construct(
        private readonly HueService $service,
    ) {}

    public function service(): HueService
    {
        return $this->service;
    }

    /**
     * @return Collection<int, Light>
     */
    public function all(): Collection
    {
        $request = $this->service->makeRequest();

        $response = $request->get('lights');

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return $response->collect()->map(fn (array $light) => LightFactory::make($light));
    }

    public function blinkAll(): bool
    {
        $request = $this->service()->makeRequest();

        $response = $request->put(
            url: '/groups/8/action',
            data: ['alert' => 'lselect'],
        );

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return true;
    }
}
