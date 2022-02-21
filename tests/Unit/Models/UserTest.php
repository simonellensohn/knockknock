<?php

use App\Models\User;

test('it has a name accessor', function () {
    $user = new User();
    $user->first_name = '::first_name::';
    $user->last_name = '::last_name::';

    expect($user->name)->toBe($user->first_name.' '.$user->last_name);
});
