<?php

use App\Http\Controllers\ReviewController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('reviews')->controller(ReviewController::class)->middleware(['auth:api'])->group(function () {
        Route::get('/{id}', 'getById');
        Route::post('/', 'create');
    });
});