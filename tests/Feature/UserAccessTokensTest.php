<?php

use App\Models\User;

test('users can create personal access tokens', function () {
    $user = User::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('users.edit', $user))
        ->post(route('user.access-tokens.store'), [
            'name' => '::name::',
        ]);

    $response->assertRedirect(route('users.edit', $user));
    expect($user->fresh()->tokens)
        ->toHaveCount(1)
        ->first()->name->toBe('::name::');
});

test('users can delete personal access tokens', function () {
    $user = User::factory()->create();
    $token = $user->createToken('::name::');

    $response = $this
        ->actingAs($user)
        ->from(route('users.edit', $user))
        ->delete(route('user.access-tokens.delete', $token->accessToken));

    $response->assertRedirect(route('users.edit', $user));
    expect($token->accessToken->fresh())->toBeNull();
});

test('users cannot delete personal access tokens of other users', function () {
    [$userA, $userB] = User::factory()->count(2)->create();
    $token = $userB->createToken('::name::');

    $response = $this
        ->actingAs($userA)
        ->from(route('users.edit', $userA))
        ->delete(route('user.access-tokens.delete', $token->accessToken));

    $response->assertNotFound();
    expect($token->accessToken->fresh())->not->toBeNull();
});
