<?php

use Illuminate\Support\Facades\Route;

Route::middleware('throttle:api')->group(function () {
    require_once __DIR__ . '/ApiRoutes/AuthRoute.php';
    require_once __DIR__ . '/ApiRoutes/CarRoute.php';
    require_once __DIR__ . '/ApiRoutes/TripRoute.php';
    require_once __DIR__ . '/ApiRoutes/ReviewRoute.php';
    require_once __DIR__ . '/ApiRoutes/UserRoute.php';
});

