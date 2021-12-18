<?php

use App\Models\Bell;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

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
    $user = User::factory()->create();
    $bell = Bell::factory()->create();
    expect($bell->user_id)->not->toBe($user->id);

    $response = $this->actingAs($user)->postJson("/api/bells/{$bell->id}/ring");

    $response->assertStatus(403);
});

test('users can ring their bell', function () {
    $user = User::factory()->create();
    $bell = Bell::factory()->for($user)->create();
    expect($bell->rings)->toBeEmpty();

    $response = $this->actingAs($user)->postJson("/api/bells/{$bell->id}/ring");

    $response->assertStatus(204);
    expect($bell->fresh()->rings)->not->toBeEmpty();
});
