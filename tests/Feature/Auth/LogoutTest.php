<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

test('logged in users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->delete(route('logout'));

    $this->assertGuest();
    $response->assertRedirect(route('login'));
});
