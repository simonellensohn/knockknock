<?php

use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Auth\Events\Lockout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Event;
use Inertia\Testing\AssertableInertia;

uses(RefreshDatabase::class);

test('logged in users cannot login again', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('login.store'));

    $response->assertRedirect(RouteServiceProvider::HOME);
});

test('guests can view the login page', function () {
    $response = $this->get(route('login'));

    $response->assertInertia(fn (AssertableInertia $assert) => $assert->component('Auth/Login'));
});

test('guests can login', function () {
    $user = User::factory()->create(['password' => '::password::']);

    $response = $this->post(route('login.store'), [
        'email' => $user->email,
        'password' => '::password::',
    ]);

    $response->assertRedirect(RouteServiceProvider::HOME);
    $this->assertAuthenticatedAs($user);
});

test('login is rate limited', function () {
    Event::fake();
    $email = 'email@email.test';
    Cache::put($email.'|127.0.0.1', 5);
    Cache::put($email.'|127.0.0.1:timer', now()->getTimestamp());

    $response = $this->from(route('login'))->post(route('login.store'), [
        'email' => $email,
        'password' => '::password::',
    ]);

    $this->assertGuest();
    $response->assertInvalid('email');
    $response->assertRedirect(route('login'));
    Event::assertDispatched(Lockout::class);
});

it('is invalid', function (array $data, array $errors) {
    User::factory()->create(['email' => 'email@test.test']);

    $response = $this->from(route('login'))->post(route('login.store'), $data);

    $response->assertInvalid($errors);
})->with([
    'email ! valid' => [['email' => 'invalid-email'], ['email' => 'email']],
    'email missing' => [['email' => null], ['email' => 'required']],

    'password missing' => [['password' => null], ['password' => 'required']],
    'password incorrect' => [
        ['email' => 'email@test.test', 'password' => 'invalid-password'],
        ['email' => 'These credentials do not match our records.'],
    ],
]);
