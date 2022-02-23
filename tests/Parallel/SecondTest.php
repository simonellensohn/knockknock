<?php

it('creates a bell', function () {
    $bell = \App\Models\Bell::factory()->create();
    $post = \App\Models\Post::factory()->create();

    dump(\Illuminate\Support\Facades\ParallelTesting::token(), \App\Models\Post::count());

    expect($bell)->not->toBeNull();
});
