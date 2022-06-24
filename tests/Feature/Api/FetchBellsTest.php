<?php

use App\Models\Bell;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('guests can not fetch any bell', function () {
    $this->assertGuest();

    $response = $this->getJson(route('api.bells.index'));

    $response->assertUnauthorized();
});

it('guests can not fetch a single bell', function () {
    $this->assertGuest();

    $response = $this->getJson(route('api.bells.show', 1));

    $response->assertUnauthorized();
});

it('authenticated users can fetch all bells', function () {
    $user = User::factory()->create();
    $bells = Bell::factory()->count(2)->create();

    $response = $this->actingAs($user)->getJson(route('api.bells.index'));

    $response->assertOk();
    $response->assertJson(
        fn (AssertableJson $json) => $json->has('data', $bells->count(), fn (AssertableJson $json) => $json->whereAllType([
            'id' => 'integer',
            'name' => 'string',
            'min_volume' => 'integer|double',
            'max_volume' => 'integer|double',
            'active' => 'boolean',
        ])
        )
    );
});

it('authenticated users can fetch a single bell', function () {
    $user = User::factory()->create();
    $bell = Bell::factory()->create();

    $response = $this->actingAs($user)->getJson(route('api.bells.show', $bell));

    $response->assertOk();
    $response->assertJson(fn (AssertableJson $json) => $json
            ->where('data.id', $bell->id)
            ->whereAllType([
                'data.id' => 'integer',
                'data.name' => 'string',
                'data.min_volume' => 'integer|double',
                'data.max_volume' => 'integer|double',
                'data.active' => 'boolean',
            ])
    );
});

it('bell has to exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->getJson(route('api.bells.show', 1));

    $response->assertNotFound();
});
