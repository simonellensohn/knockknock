<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use Illuminate\Validation\ValidationException;

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

expect()->extend('toBeInvalid', function ($errors) {
    try {
        $this->value->__invoke();
        test()->fail('No validation exception was thrown!');
    } catch (ValidationException $exception) {
        foreach ($errors as $key => $error) {
            expect(json_encode($exception->errors()[$key]))->toContain($error);
        }
    }
});

function fixture(string $name, bool $decode = true): string|array
{
    $file = file_get_contents(
        filename: base_path("tests/Fixtures/$name.json"),
    );

    if(! $file) {
        throw new InvalidArgumentException(
            message: "Cannot find fixture: [$name] at tests/Fixtures/$name.json",
        );
    }

    if (! $decode) {
        return $file;
    }

    return json_decode(
        json: $file,
        associative: true,
    );
}
