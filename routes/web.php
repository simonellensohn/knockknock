<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HueCallbackController;
use App\Http\Controllers\PushSubscriptionController;
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

    Route::post('push/subscriptions', [PushSubscriptionController::class, 'update']);
    Route::post('push/subscriptions/delete', [PushSubscriptionController::class, 'destroy']);

    Route::get('hue', function (HueApi $api) {
        return response()->json($api->fetchLights());
    })->middleware('auth');
});
