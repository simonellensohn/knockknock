<?php

namespace App\Services\Hue\Resources;

use App\Services\Hue\DataObjects\User;
use App\Services\Hue\Exceptions\HueRequestException;
use App\Services\Hue\Factories\UserFactory;
use App\Services\Hue\HueService;
use App\Services\Hue\Requests\CreateUser;

class ConfigurationResource
{
    public function __construct(
        private readonly HueService $service,
    ) {
    }

    public function service(): HueService
    {
        return $this->service;
    }

    public function link(): bool
    {
        $request = $this->service()->makeRequest();

        $response = $request
            ->baseUrl($this->service->baseUrl)
            ->put('/route/api/0/config', ['linkbutton' => true]);

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return true;
    }

    public function create(CreateUser $requestBody): User
    {
        $request = $this->service()->makeRequest();

        $response = $request
            ->baseUrl($this->service->baseUrl)
            ->post('/route/api', $requestBody->toRequest());

        if ($response->failed()) {
            throw new HueRequestException($response);
        }

        return UserFactory::make((array) $response->json('0.success'));
    }
}
