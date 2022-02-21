<?php

use App\Models\User;

test('users can update their push subscription', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put(route('user.push.subscriptions.store'), [
        'endpoint' => '::endpoint::',
    ]);

    $response->assertRedirect();
    expect($user->pushSubscriptions)
        ->toHaveCount(1)
        ->first()->endpoint->toBe('::endpoint::');
});

it('requires an endpoint for update', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->put(route('user.push.subscriptions.store'));

    $response->assertInvalid('endpoint');
    expect($user->pushSubscriptions)->toBeEmpty();
});

test('users can delete their push subscription', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('::endpoint::');
    expect($user->pushSubscriptions)->toHaveCount(1);

    $response = $this->actingAs($user)->delete(route('user.push.subscriptions.destroy'), [
        'endpoint' => '::endpoint::',
    ]);

    $response->assertRedirect();
    expect($user->fresh()->pushSubscriptions)->toBeEmpty();
});

it('requires an endpoint for delete', function () {
    $user = User::factory()->create();
    $user->updatePushSubscription('::endpoint::');
    expect($user->pushSubscriptions)->toHaveCount(1);

    $response = $this->actingAs($user)->delete(route('user.push.subscriptions.destroy'));

    $response->assertInvalid('endpoint');
    expect($user->pushSubscriptions)->toHaveCount(1);
});
