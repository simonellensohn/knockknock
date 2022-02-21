<?php

use App\Actions\UpdateUser;
use App\Contracts\Actions\UpdatesUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('can update an existing user', function () {
    $user = User::factory()->create();
    $action = app(UpdateUser::class);

    $user = $action($user, [
        'first_name' => '::first_name::',
        'last_name' => '::last_name::',
        'email' => 'email@test.test',
        'password' => '::password::',
    ]);

    expect($user)
        ->first_name->toBe('::first_name::')
        ->last_name->toBe('::last_name::')
        ->email->toBe('email@test.test');
    expect(Hash::check('::password::', $user->password))->toBeTrue();
});

it('password is optional when updating an existing user', function () {
    $user = User::factory()->create(['password' => '::password::']);
    $action = app(UpdateUser::class);

    $user = $action($user, [
        'first_name' => '::first_name::',
        'last_name' => '::last_name::',
        'email' => 'email@test.test',
    ]);

    expect($user)
        ->first_name->toBe('::first_name::')
        ->last_name->toBe('::last_name::')
        ->email->toBe('email@test.test');
    expect(Hash::check('::password::', $user->password))->toBeTrue();
});

it('is invalid', function (array $data, array $errors) {
    $user = User::factory()->create([]);
    User::factory()->create(['email' => 'email@test.test']);

    $action = app(UpdatesUser::class);

    expect(fn () => $action($user, $data))->toBeInvalid($errors);
})->with([
    'first name missing' => [['first_name' => null], ['first_name' => 'required']],
    'first name > 50 characters' => [['first_name' => str_repeat('a', 51)], ['first_name' => 'must not be greater than 50 characters']],

    'last name missing' => [['last_name' => null], ['last_name' => 'required']],
    'last name > 50 characters' => [['last_name' => str_repeat('a', 51)], ['last_name' => 'must not be greater than 50 characters']],

    'email ! valid' => [['email' => 'invalid-email'], ['email' => 'email']],
    'email taken' => [['email' => 'email@test.test'], ['email' => 'taken']],
    'email > 50 characters' => [['email' => str_repeat('a', 51)], ['email' => 'must not be greater than 50 characters']],
]);
