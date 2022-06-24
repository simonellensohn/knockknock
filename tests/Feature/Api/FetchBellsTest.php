<?php

use App\Models\Bell;
use App\Models\User;
use Illuminate\Testing\Fluent\AssertableJson;

it('guests can not fetch any bell', function () {
    $this->assertGuest();

    $response = $this->getJson(route('api.bells.index'));

    $response->assertUnauthorized();
});

it('authenticated users can fetch all bells', function () {
    $user = User::factory()->create();
    $bells = Bell::factory()->count(2)->create();

    $response = $this->actingAs($user)->getJson(route('api.bells.index'));

    $response->assertOk();
    $response->assertJson(function (AssertableJson $json) use ($bells) {
        $json->has('data', $bells->count(), function (AssertableJson $json) {
            $json->whereAllType([
                'id' => 'integer',
                'name' => 'string',
                'min_volume' => 'integer|double',
                'max_volume' => 'integer|double',
                'active' => 'boolean',
            ]);
        });
    });
});
