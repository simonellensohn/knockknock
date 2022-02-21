<?php

use App\Events\BellRinging;
use App\Models\Bell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

function getRoute(int $bellId): string
{
    return route('api.bells.ring', $bellId);
}

test('guests cannot ring a bell', function () {
    $response = $this->postJson(getRoute(1));

    $response->assertUnauthorized();
});

test('bell has to exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson(getRoute(999));

    $response->assertNotFound();
});

test('user can only ring their own bells', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->create();
    expect($bell->user_id)->not->toBe($user->id);

    $response = $this->actingAs($user)->postJson(getRoute($bell->id), ['volume' => 10]);

    $response->assertForbidden();
    Event::assertNotDispatched(BellRinging::class);
});

test('users can ring their bell', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->for($user)->create();
    expect($bell->rings)->toBeEmpty();

    $response = $this->actingAs($user)->postJson(getRoute($bell->id), [
        'volume' => 10,
        'events' => [11, 9, 10],
    ]);

    $response->assertNoContent();
    expect($bell->fresh()->rings)->not->toBeEmpty();
    Event::assertDispatched(BellRinging::class);
});

test('ringing a bell does not dispatch an event if the bell is inactive', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->for($user)->create(['active' => false]);
    expect($bell->rings)->toBeEmpty();

    $response = $this->actingAs($user)->postJson(getRoute($bell->id), [
        'volume' => 10,
        'events' => [11, 9, 10],
    ]);

    $response->assertNoContent();
    expect($bell->fresh()->rings)->not->toBeEmpty();
    Event::assertNotDispatched(BellRinging::class);
});
