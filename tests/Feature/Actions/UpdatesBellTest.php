<?php

use App\Actions\UpdateBell;
use App\Contracts\Actions\UpdatesBell;
use App\Models\Bell;

it('can update a bell', function () {
    $bell = Bell::factory()->create([
        'min_volume' => 5,
        'max_volume' => 10,
        'active' => false,
    ]);
    $action = app(UpdateBell::class);

    $bell = $action($bell, [
        'name' => '::name::',
        'min_volume' => 1,
        'max_volume' => 3,
        'active' => true,
    ]);

    expect($bell)
        ->name->toBe('::name::')
        ->min_volume->toBe(1.0)
        ->max_volume->toBe(3.0)
        ->active->toBe(true);
});

it('is invalid', function (array $data, array $errors) {
    Bell::factory()->create(['name' => 'Existing Bell', 'active' => false]);
    $bell = Bell::factory()->create([
        'name' => 'Current Bell',
        'min_volume' => 5,
        'max_volume' => 10,
        'active' => false,
    ]);

    $action = app(UpdatesBell::class);

    expect(fn () => $action($bell, $data))->toBeInvalid($errors);
})->with([
    'name taken' => [['email' => 'Existing Name'], ['name' => 'required']],
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
