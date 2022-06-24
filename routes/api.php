<?php

use App\Http\Controllers\Api\BellController;
use App\Http\Controllers\Api\RingBellController;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/bells', [BellController::class, 'index'])->name('api.bells.index');
    Route::get('/bells/{bell}', [BellController::class, 'show'])->name('api.bells.show');
    Route::post('/bells/{bell}/ring', RingBellController::class)->name('api.bells.ring');
});
