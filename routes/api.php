<?php

use App\Http\Controllers\Api\BellController;
use App\Http\Controllers\Api\RingBellController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bells', BellController::class)->name('api.bells.index');
    Route::post('/bells/{bell}/ring', RingBellController::class)->name('api.bells.ring');
});
