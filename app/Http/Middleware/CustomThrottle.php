<?php

namespace App\Http\Middleware;

use Illuminate\Routing\Middleware\ThrottleRequests;
use Symfony\Component\HttpFoundation\Response;

class CustomThrottle extends ThrottleRequests
{
    protected function buildResponse($key, $maxAttempts)
    {
        $retryAfter = $this->limiter->availableIn($key);

        return response()->json([
            'success' => false,
            'message' => "Слишком много запросов. Попробуйте через {$retryAfter} секунд.",
        ], Response::HTTP_TOO_MANY_REQUESTS)->header('Retry-After', $retryAfter);
    }
}