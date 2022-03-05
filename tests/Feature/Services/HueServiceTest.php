<?php

use App\Services\Hue\DataObjects\Light;
use App\Services\Hue\DataObjects\OAuthToken;
use App\Services\Hue\Exceptions\HueRequestException;
use App\Services\Hue\Factories\TokenFactory;
use App\Services\Hue\HueService;
use App\Services\Hue\Requests\CreateUser;
use App\Services\Hue\Resources\ConfigurationResource;
use App\Services\Hue\Resources\LightResource;
use App\Services\Hue\Resources\TokenResource;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

it('can build a new Hue Service', function (string $string) {
    expect(
        new HueService(
            baseUrl: $string,
            appId: $string,
            clientId: $string,
            clientSecret: $string,
            username: $string,
        )
    )->toBeInstanceOf(HueService::class);
})->with('strings');

it('can create a Pending Request', function (string $string) {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));

    $service = new HueService(
        baseUrl: $string,
        appId: $string,
        clientId: $string,
        clientSecret: $string,
        username: $string,
    );

    expect(
        $service->makeRequest(),
    )->toBeInstanceOf(PendingRequest::class);
})->with('strings');

it('can resolve a Hue Service from the container', function () {
    expect(
        resolve(HueService::class)
    )->toBeInstanceOf(HueService::class);
});

it('can create a Pending Request from the Resolved Service', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));

    expect(
        resolve(HueService::class)->makeRequest()
    )->toBeInstanceOf(PendingRequest::class);
});

it('resolves as a singleton only', function () {
    $service = resolve(HueService::class);

    expect(resolve(HueService::class))->toEqual($service);
});

it('can get a token resource', function () {
    $hue = resolve(HueService::class);

    expect($hue->token())->toBeInstanceOf(TokenResource::class);
});

it('can get a configuration resource', function () {
    $hue = resolve(HueService::class);

    expect($hue->configuration())->toBeInstanceOf(ConfigurationResource::class);
});

it('can get a light resource', function () {
    $hue = resolve(HueService::class);

    expect($hue->light())->toBeInstanceOf(LightResource::class);
});

it('can fetch and store an OAuth token to the disk', function () {
    Storage::fake('local');
    $hue = resolve(HueService::class);
    HueService::fake([
        '/v2/oauth2/token' => Http::response(
            body:   fixture('Hue/OAuthToken'),
            status: Response::HTTP_OK,
        ),
    ]);

    $token = $hue->token()->fetch('::code::');

    expect($token)->toBeInstanceOf(OAuthToken::class);
    Storage::assertExists('hue.json');
    expect($token)->toEqual(TokenFactory::make(readFromFile: true));
});

it('throws custom exception when fetching a token', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/v2/oauth2/token' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->token()->fetch('::code::');
})->expectException(HueRequestException::class);

it('reuses existing OAuth token', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $hue = resolve(HueService::class);
    $existingToken = TokenFactory::make(readFromFile: true);

    $token = $hue->token()->get();

    expect($token)->toEqual($existingToken);
});

it('fetches OAuth token when current one is empty', function () {
    Storage::fake('local');
    Storage::disk('local')->put('hue.json', fixture('Hue/EmptyOAuthToken', decode: false));
    $hue = resolve(HueService::class);
    $fake = HueService::fake([
        '/v2/oauth2/token' => Http::response(
            body:   fixture('Hue/OAuthToken'),
            status: Response::HTTP_OK,
        ),
    ]);

    $token = $hue->token()->get('::code::');

    $fake->assertSentCount(1);
    expect($token)->toBeInstanceOf(OAuthToken::class);
    Storage::assertExists('hue.json');
    expect($token)->toEqual(TokenFactory::make(readFromFile: true));
});

it('refreshes expired OAuth token', function (string $json) {
    Storage::fake('local');
    Storage::disk('local')->put('hue.json', $json);
    $hue = resolve(HueService::class);
    $fake = HueService::fake([
        '/v2/oauth2/token' => Http::response(
            body:   fixture('Hue/OAuthToken'),
            status: Response::HTTP_OK,
        ),
    ]);

    $token = $hue->token()->get();

    $fake->assertSentCount(1);
    expect($token)->toBeInstanceOf(OAuthToken::class);
    Storage::assertExists('hue.json');
    expect($token)->toEqual(TokenFactory::make(readFromFile: true));
})->with([
    fn () => fixture('Hue/ExpiredOAuthToken', decode: false),
    fn () => fixture('Hue/ExpiredOAuthTokenBlankDate', decode: false),
]);

it('throws custom exception when refreshing a token', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/v2/oauth2/token' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->token()->refresh(new OAuthToken());
})->expectException(HueRequestException::class);

it('can fetch all lights', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/*/lights' => Http::response(
            body:   fixture('Hue/Lights'),
            status: Response::HTTP_OK,
        ),
    ]);

    $lights = $hue->light()->all();

    expect($lights)->toBeInstanceOf(Collection::class);

    $lights->each(function (Light $light) {
        expect($light->attributes)->toBeArray();
    });
});

it('throws custom exception when fetching all lights', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/*/lights' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->light()->all();
})->expectException(HueRequestException::class);

it('can blink all lights within a group', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/*/groups/*/action' => Http::response(status: Response::HTTP_OK),
    ]);

    $blinked = $hue->light()->blinkAll();

    expect($blinked)->toBeTrue();
});

it('throws custom exception when blinking all lights of a group', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/*/groups/*/action' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->light()->blinkAll();
})->expectException(HueRequestException::class);

it('can link the bridge', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/0/config' => Http::response(status: Response::HTTP_OK),
    ]);

    $blinked = $hue->configuration()->link();

    expect($blinked)->toBeTrue();
});

it('throws custom exception when linking the bridge', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api/0/config' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->configuration()->link();
})->expectException(HueRequestException::class);

it('can create a user', function () {
    Storage::fake('local');
    Storage::put('hue.json', fixture('Hue/OAuthToken', decode: false));
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api' => Http::response(
            body: fixture('Hue/CreateUser'),
            status: Response::HTTP_OK
        ),
    ]);

    $user = $hue->configuration()->create(new CreateUser('1234'));

    expect($user)->name->toBe('::username::');
});

it('throws custom exception when creating a user', function () {
    $hue = resolve(HueService::class);
    HueService::fake([
        '/route/api' => Http::response(status: Response::HTTP_NOT_FOUND),
    ]);

    $hue->configuration()->create(new CreateUser('1234'));
})->expectException(HueRequestException::class);

it('can create a new configuration resource manually', function () {
    expect(
        new ConfigurationResource($service = resolve(HueService::class))
    )
        ->toBeInstanceOf(ConfigurationResource::class)
        ->service()->toEqual($service);
});

it('can create a new light resource manually', function () {
    expect(
        new LightResource($service = resolve(HueService::class))
    )
        ->toBeInstanceOf(LightResource::class)
        ->service()->toEqual($service);

});

it('can create a new token resource manually', function () {
    expect(
        new TokenResource($service = resolve(HueService::class))
    )
        ->toBeInstanceOf(TokenResource::class)
        ->service()->toEqual($service);
});
