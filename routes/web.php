<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HueCallbackController;
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

// Dashboard

Route::get('/', [DashboardController::class, 'index'])
    ->name('dashboard')
    ->middleware('auth');


Route::get('hue', function (HueApi $api) {
    return response()->json($api->fetchLights());
})->middleware('auth');

Route::get('hue/callback', HueCallbackController::class);
