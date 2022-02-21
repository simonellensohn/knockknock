<?php

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Sequence;

it('toggles all bells of the user', function () {
    $user = User::factory()
        ->hasBells(2, new Sequence(['active' => true], ['active' => false]))
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(route('bells.index'))
        ->post(route('bells.toggle'), ['active' => true]);

    $response->assertRedirect(route('bells.index'));
    expect($user->bells()->where('active', true)->doesntExist())->toBeFalse();
});

it('defaults to inactive', function () {
    $user = User::factory()
        ->hasBells(2, new Sequence(['active' => true], ['active' => false]))
        ->create();

    $response = $this
        ->actingAs($user)
        ->from(route('bells.index'))
        ->post(route('bells.toggle'));

    $response->assertRedirect(route('bells.index'));
    expect($user->bells()->where('active', true)->doesntExist())->toBeTrue();
});
