<?php

use App\Contracts\Actions\CreatesBell;
use App\Contracts\Actions\UpdatesBell;
use App\Models\Bell;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('users can get all bells including their ring count', function () {
    $user = User::factory()->create();
    $bellA = Bell::factory()->hasRings(2)->create();
    $bellB = Bell::factory()->hasRings(1)->create();

    $response = $this->actingAs($user)->get(route('bells.index'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Bells/Index')
        ->has('bells.data', 2)
        ->where('bells.data.0.rings_count', $bellA->rings->count())
        ->where('bells.data.1.rings_count', $bellB->rings->count())
    );
});

test('guests cannot view the create form', function () {
    $this->assertGuest();

    $response = $this->get(route('bells.create'));

    $response->assertRedirect(route('login'));
});

test('users can view the create form', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->get(route('bells.create'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Bells/Create'));
});

test('users can create a new bell', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('bells.store'), [
        'name' => '::name::',
        'threshold' => 10,
    ]);

    $response->assertRedirect(route('bells.index'));
    expect(Bell::query()->latest()->first())
        ->user_id->toBe($user->id)
        ->name->toBe('::name::')
        ->threshold->toBe(10.0);
})->shouldHaveCalledAction(CreatesBell::class);

test('guests cannot view the edit form', function () {
    $this->assertGuest();
    $bell = Bell::factory()->create();

    $response = $this->get(route('bells.edit', $bell));

    $response->assertRedirect(route('login'));
});

test('users can view the edit form', function () {
    $user = User::factory()->create();
    $bell = Bell::factory()->create();

    $response = $this->actingAs($user)->get(route('bells.edit', $bell));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page->component('Bells/Edit'));
});

test('users can update an existing bell', function () {
    $user = User::factory()->create();
    $bell = Bell::factory()->create(['active' => false]);
    expect($bell)->user_id->not->toBe($user->id);

    $response = $this->actingAs($user)
        ->from(route('bells.edit', $bell))
        ->put(route('bells.update', $bell), [
            'name' => '::name::',
            'threshold' => 10,
            'active' => true,
        ]);

    $response->assertRedirect(route('bells.edit', $bell));
    expect($bell->fresh())
        ->user_id->not->toBe($user->id)
        ->name->toBe('::name::')
        ->threshold->toBe(10.0)
        ->active->toBeTrue();
})->shouldHaveCalledAction(UpdatesBell::class);

test('users can delete bells', function () {
    $user = User::factory()->create();
    $bell = Bell::factory()->create();

    $response = $this
        ->actingAs($user)
        ->from(route('bells.edit', $bell))
        ->delete(route('bells.delete', $bell));

    $response->assertRedirect(route('bells.index'));
    expect($bell->fresh())->toBeNull();
});
