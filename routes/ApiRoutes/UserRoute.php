<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'v1'], function () {
    Route::prefix('users')->controller(UserController::class)->group(function () {
        Route::get('/current/info', 'currentUserInfo');
    });
});
