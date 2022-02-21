<?php

use App\Models\Ring;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;

test('it renders the dasboard view', function () {
    $user = User::factory()->create();
    $lastRing = Ring::factory()->create(['volume' => 5.50]);
    Ring::factory()->create(['volume' => 4.50, 'created_at' => now()->subDay()]);

    $response = $this->actingAs($user)->get(route('dashboard'));

    $response->assertOk();
    $response->assertInertia(fn (Assert $page) => $page
        ->component('Dashboard/Index')
        ->where('totalRings', 2)
        ->where('lastRing', [
            'date' => $lastRing->created_at->format('d.m.Y H:i:s'),
            'readable' => $lastRing->created_at->diffForHumans(),
        ])
        ->where('averageVolume', 5)
    );
});

test('guests cannot view the dashboard', function () {
    $this->assertGuest();

    $response = $this->get(route('dashboard'));

    $response->assertRedirect(route('login'));
});
