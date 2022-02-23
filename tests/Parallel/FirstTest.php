<?php

it('creates a user', function () {
    $user = \App\Models\User::factory()->create();
    $post = \App\Models\Post::first();

    dump(\Illuminate\Support\Facades\ParallelTesting::token(), \App\Models\Post::count());

    expect($user)->not->toBeNull();
});
