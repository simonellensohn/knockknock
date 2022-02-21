<?php

use App\Services\Hue\HueService;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;

test('code is required', function () {
    $response = $this->get('/hue/callback');

    $response->assertInvalid('code');
});

it('fetches an OAuth token', function () {
    HueService::fake([
        '/v2/oauth2/token' => Http::response(status: Response::HTTP_OK),
        '/route/api/0/config' => Http::response(status: Response::HTTP_OK),
        '/route/api' => Http::response(
            body: fixture('Hue/CreateUser'),
            status: Response::HTTP_OK
        ),
    ]);
    $response = $this->get('/hue/callback?code=::code::');

    $response->assertOk();
    $response->assertJson([
        'data' => [
            'username' => '::username::',
        ],
    ]);
});
