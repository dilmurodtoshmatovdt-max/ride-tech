<?php

use App\Http\Controllers\TripController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('trips')->controller(TripController::class)->middleware(['auth:api'])->group(function () {
        Route::get('/', 'getAll');
        Route::get('/{id}', 'getById');
        Route::post('/', 'create');
        Route::put('/{id}/', 'update');
        Route::put('/{id}/cancel', 'cancel');
        Route::put('/{id}/reject', 'reject');
        Route::put('/{id}/assign', 'assign');
        Route::put('/{id}/arrive', 'arrive');
        Route::put('/{id}/start', 'start');
        Route::put('/{id}/finish', 'finish');
    });
});