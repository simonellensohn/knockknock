<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BellsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HueCallbackController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\RingsController;
use App\Http\Controllers\ToggleBellsController;
use App\Http\Controllers\UserAccessTokensController;
use App\Http\Controllers\UsersController;
use App\Http\Middleware\HandleInertiaRequests;
use App\Services\Hue\HueService;
use Illuminate\Support\Facades\Route;

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('hue/callback', HueCallbackController::class)
    ->withoutMiddleware(HandleInertiaRequests::class);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::post('users', [UsersController::class, 'store'])->name('users.store');
    Route::get('users/create', [UsersController::class, 'create'])->name('users.create');
    Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.delete');

    Route::post('user/access-tokens', [UserAccessTokensController::class, 'store'])->name('user.access-tokens.store');
    Route::delete('user/access-tokens/{token}', [UserAccessTokensController::class, 'destroy'])->name('user.access-tokens.delete');
    Route::put('user/push/subscriptions', [PushSubscriptionController::class, 'update'])->name('user.push.subscriptions.store');
    Route::delete('user/push/subscriptions/delete', [PushSubscriptionController::class, 'destroy'])->name('user.push.subscriptions.destroy');

    Route::get('bells', [BellsController::class, 'index'])->name('bells.index');
    Route::post('bells', [BellsController::class, 'store'])->name('bells.store');
    Route::post('bells/toggle', ToggleBellsController::class)->name('bells.toggle');
    Route::get('bells/create', [BellsController::class, 'create'])->name('bells.create');
    Route::get('bells/{bell}/edit', [BellsController::class, 'edit'])->name('bells.edit');
    Route::put('bells/{bell}', [BellsController::class, 'update'])->name('bells.update');
    Route::delete('bells/{bell}', [BellsController::class, 'destroy'])->name('bells.delete');

    Route::get('rings', [RingsController::class, 'index'])->name('rings.index');
    Route::get('hue', fn (HueService $service) => $service->light()->all());
});
