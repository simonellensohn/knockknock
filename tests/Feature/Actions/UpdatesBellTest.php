<?php

use App\Actions\UpdateBell;
use App\Contracts\Actions\UpdatesBell;
use App\Models\Bell;

it('can update a bell', function () {
    $bell = Bell::factory()->create(['threshold' => 10, 'active' => false]);
    $action = app(UpdateBell::class);

    $bell = $action($bell, [
        'name' => '::name::',
        'threshold' => 5,
        'active' => true,
    ]);

    expect($bell)
        ->name->toBe('::name::')
        ->threshold->toBe(5.0)
        ->active->toBe(true);
});

it('is invalid', function (array $data, array $errors) {
    Bell::factory()->create(['name' => 'Existing Bell', 'threshold' => 5, 'active' => false]);
    $bell = Bell::factory()->create(['name' => 'Current Bell', 'threshold' => 10, 'active' => false]);

    $action = app(UpdatesBell::class);

    expect(fn () => $action($bell, $data))->toBeInvalid($errors);
})->with([
    'name taken' => [['email' => 'Existing Name'], ['name' => 'required']],
    'name ! string' => [['name' => 123], ['name' => 'string']],
    'name > 50 characters' => [['name' => str_repeat('a', 51)], ['name' => 'must not be greater than 50 characters']],

    'threshold taken' => [['threshold' => 5], ['threshold' => 'taken']],
    'threshold ! numeric' => [['threshold' => 'a1'], ['threshold' => 'number']],
    'threshold < 1' => [['threshold' => 0], ['threshold' => 'between 1 and 100']],
    'threshold > 100' => [['threshold' => 101], ['threshold' => 'between 1 and 100']],
]);
