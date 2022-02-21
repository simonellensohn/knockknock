<?php

use App\Contracts\Actions\CreatesUser;
use App\Contracts\Actions\UpdatesUser;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('users can get all users', function () {
    [$user] = User::factory()->count(3)->create();

    $response = $this->actingAs($user)->get(route('users.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Users/Index')
        ->has('users.data', 3)
    );
});

test('guests cannot view the create form', function () {
    $this->assertGuest();

    $response = $this->get(route('users.create'));

    $response->assertRedirect(route('login'));
});

test('users can view the create form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('users.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Users/Create'));
});

test('users can create a new user', function () {
    $user = User::factory()->create(['created_at' => now()->subDay()]);

    $response = $this
        ->actingAs($user)
        ->post(route('users.store'), [
            'first_name' => '::first_name::',
            'last_name' => '::last_name::',
            'email' => 'mail@test.test',
            'password' => '::password::',
        ]);

    $response->assertRedirect(route('users.index'));
    expect(User::query()->latest()->first())
        ->first_name->toBe('::first_name::')
        ->last_name->toBe('::last_name::')
        ->email->toBe('mail@test.test')
        ->password->not->toBeNull()->not->toBe('::password::');
})->shouldHaveCalledAction(CreatesUser::class);

test('guests cannot view the edit form', function () {
    $this->assertGuest();
    $user = User::factory()->create();

    $response = $this->get(route('users.edit', $user));

    $response->assertRedirect(route('login'));
});

test('users can view the edit form', function () {
    [$userA, $userB] = User::factory()->count(2)->create();

    $response = $this->actingAs($userA)->get(route('users.edit', $userB));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Users/Edit'));
});

test('users can update another user', function () {
    [$userA, $userB] = User::factory()->count(2)->create();

    $response = $this->actingAs($userA)
        ->from(route('users.edit', $userB))
        ->put(route('users.update', $userB), [
            'first_name' => '::first_name::',
            'last_name' => '::last_name::',
            'email' => 'mail@test.test',
        ]);

    $response->assertRedirect(route('users.edit', $userB));
    expect($userB->fresh())
        ->first_name->toBe('::first_name::')
        ->last_name->toBe('::last_name::')
        ->email->toBe('mail@test.test')
        ->password->not->toBeNull();
})->shouldHaveCalledAction(UpdatesUser::class);

test('users can delete other users', function () {
    [$userA, $userB] = User::factory()->count(2)->create();

    $response = $this
        ->actingAs($userA)
        ->from(route('users.edit', $userB))
        ->delete(route('users.delete', $userB));

    $response->assertRedirect(route('users.index'));
    expect($userB->fresh())->toBeNull();
});
