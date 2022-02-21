<?php

use App\Models\Bell;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('guests cannot get all rings', function () {
    $this->assertGuest();

    $response = $this->get(route('rings.index'));

    $response->assertRedirect(route('login'));
});

test('users can get all rings', function () {
    $user = User::factory()->create();
    Bell::factory()->hasRings(2)->create();
    Bell::factory()->hasRings(1)->create();

    $response = $this->actingAs($user)->get(route('rings.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Rings/Index')
        ->has('rings.data', 3)
    );
});
