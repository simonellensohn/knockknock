<?php

use App\Events\BellRinging;
use App\Models\Bell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Event;

uses(RefreshDatabase::class);

test('guests cannot ring a bell', function () {
    $response = $this->postJson('/api/bells/1/ring');

    $response->assertStatus(401);
});

test('bell has to exist', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->postJson('/api/bells/999/ring');

    $response->assertStatus(404);
});

test('user can only ring their own bells', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->create();
    expect($bell->user_id)->not->toBe($user->id);

    $response = $this->actingAs($user)->postJson("/api/bells/{$bell->id}/ring", ['volume' => 10]);

    $response->assertStatus(403);
    Event::assertNotDispatched(BellRinging::class);
});

test('users can ring their bell', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->for($user)->create();
    expect($bell->rings)->toBeEmpty();

    $response = $this->actingAs($user)->postJson("/api/bells/{$bell->id}/ring", [
        'volume' => 10,
        'events' => [11, 9, 10],
    ]);

    $response->assertStatus(204);
    expect($bell->fresh()->rings)->not->toBeEmpty();
    Event::assertDispatched(BellRinging::class);
});

test('ringing a bell does not dispatch an event if the bell is inactive', function () {
    Event::fake();
    $user = User::factory()->create();
    $bell = Bell::factory()->for($user)->create(['active' => false]);
    expect($bell->rings)->toBeEmpty();

    $response = $this->actingAs($user)->postJson("/api/bells/{$bell->id}/ring", [
        'volume' => 10,
        'events' => [11, 9, 10],
    ]);

    $response->assertStatus(204);
    expect($bell->fresh()->rings)->not->toBeEmpty();
    Event::assertNotDispatched(BellRinging::class);
});
