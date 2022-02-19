<?php

use App\Models\Bell;
use App\Actions\CreateBell;
use App\Models\User;
use App\Contracts\Actions\CreatesBell;

it('can create a bell belonging to a user', function () {
    $user = User::factory()->create();
    $action = app(CreateBell::class);

    $bell = $action($user, [
        'name' => 'Main Door',
        'threshold' => 10,
    ]);

    expect($bell)
        ->user_id->toBe($user->id)
        ->name->toBe('Main Door')
        ->threshold->toBe(10.0);
});

it('is invalid', function (array $data, array $errors) {
    $user = User::factory()->create();
    Bell::factory()->create(['name' => 'Main Door', 'threshold' => 10]);

    $action = app(CreatesBell::class);

    expect(fn () => $action($user, $data))->toBeInvalid($errors);
})->with([
    'name taken' => [['email' => 'Main Door'], ['name' => 'required']],
    'name ! string' => [['name' => 123], ['name' => 'string']],
    'name > 50 characters' => [['name' => str_repeat('a', 51)], ['name' => 'must not be greater than 50 characters']],

    'threshold taken' => [['threshold' => 10], ['threshold' => 'taken']],
    'threshold ! numeric' => [['threshold' => 'a1'], ['threshold' => 'number']],
    'threshold < 1' => [['threshold' => 0], ['threshold' => 'between 1 and 100']],
    'threshold > 100' => [['threshold' => 101], ['threshold' => 'between 1 and 100']],
]);
