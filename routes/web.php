<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\BellsController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HueCallbackController;
use App\Http\Controllers\PushSubscriptionController;
use App\Http\Controllers\UserAccessTokensController;
use App\Http\Controllers\UsersController;
use App\Services\Hue\HueApi;
use Illuminate\Support\Facades\Route;

// Auth

Route::get('login', [AuthenticatedSessionController::class, 'create'])
    ->name('login')
    ->middleware('guest');

Route::post('login', [AuthenticatedSessionController::class, 'store'])
    ->name('login.store')
    ->middleware('guest');

Route::delete('logout', [AuthenticatedSessionController::class, 'destroy'])
    ->name('logout');

Route::get('hue/callback', HueCallbackController::class);

Route::middleware('auth')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('users', [UsersController::class, 'index'])->name('users.index');
    Route::get('users/{user}/edit', [UsersController::class, 'edit'])->name('users.edit');
    Route::put('users/{user}', [UsersController::class, 'update'])->name('users.update');
    Route::delete('users/{user}', [UsersController::class, 'destroy'])->name('users.delete');
    Route::post('users/{user}/access-tokens', [UserAccessTokensController::class, 'store'])->name('users.access-tokens.store');
    Route::delete('users/{user}/access-tokens/{token}', [UserAccessTokensController::class, 'destroy'])->name('users.access-tokens.delete');

    Route::get('bells', [BellsController::class, 'index'])->name('bells.index');
    Route::post('bells', [BellsController::class, 'store'])->name('bells.store');
    Route::get('bells/create', [BellsController::class, 'create'])->name('bells.create');
    Route::get('bells/{bell}/edit', [BellsController::class, 'edit'])->name('bells.edit');
    Route::put('bells/{bell}', [BellsController::class, 'update'])->name('bells.update');
    Route::delete('bells/{bell}', [BellsController::class, 'destroy'])->name('bells.delete');

    Route::post('push/subscriptions', [PushSubscriptionController::class, 'update']);
    Route::post('push/subscriptions/delete', [PushSubscriptionController::class, 'destroy']);

    Route::get('hue', function (HueApi $api) {
        return response()->json($api->fetchLights());
    });
});
