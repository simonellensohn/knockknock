<?php

use App\Actions\CreateBell;
use App\Contracts\Actions\CreatesBell;
use App\Models\Bell;
use App\Models\User;

it('can create a bell belonging to a user', function () {
    $user = User::factory()->create();
    $action = app(CreateBell::class);

    $bell = $action($user, [
        'name' => 'Main Door',
        'min_volume' => 5,
        'max_volume' => 10,
    ]);

    expect($bell)
        ->user_id->toBe($user->id)
        ->name->toBe('Main Door')
        ->min_volume->toBe(5.0)
        ->max_volume->toBe(10.0);
});

it('is invalid', function (array $data, array $errors) {
    $user = User::factory()->create();
    Bell::factory()->create([
        'name' => 'Main Door',
        'min_volume' => 5,
        'max_volume' => 10,
    ]);

    $action = app(CreatesBell::class);

    expect(fn () => $action($user, $data))->toBeInvalid($errors);
})->with([
    'name taken' => [['email' => 'Main Door'], ['name' => 'required']],
    'name ! string' => [['name' => 123], ['name' => 'string']],
    'name > 50 characters' => [['name' => str_repeat('a', 51)], ['name' => 'must not be greater than 50 characters']],

    'min_volume ! numeric' => [['min_volume' => 'a1'], ['min_volume' => 'number']],
    'min_volume < 1' => [['min_volume' => 0], ['min_volume' => 'between 1 and 100']],
    'min_volume > 100' => [['min_volume' => 101], ['min_volume' => 'between 1 and 100']],

    'max_volume ! numeric' => [['max_volume' => 'a1'], ['max_volume' => 'number']],
    'max_volume < 1' => [['max_volume' => 0], ['max_volume' => 'between 1 and 100']],
    'max_volume > 100' => [['max_volume' => 101], ['max_volume' => 'between 1 and 100']],

    'min_volume = max_volume' => [['min_volume' => 5, 'max_volume' => 5], ['min_volume' => 'must be less than 5']],
    'min_volume > max_volume' => [['min_volume' => 5, 'max_volume' => 4], ['min_volume' => 'must be less than 4']],
]);
