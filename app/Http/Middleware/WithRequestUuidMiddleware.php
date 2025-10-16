<?php

namespace App\Http\Middleware;

use Str;
use Closure;
use Illuminate\Http\Request;
use App\Services\Shared\Auth\AuthService;
use Auth;
use Symfony\Component\HttpFoundation\Response;

class  WithRequestUuidMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    //public function __construct(public AuthService $authService) {}

    public function handle(Request $request, Closure $next): Response
    {
        //$this->authService->updateLastVisitedAt(Auth::id());
        $request->headers->get('X-REQUEST-ID', null) ??  $request->headers->set('X-REQUEST-ID', Str::uuid());
        return $next($request);
    }
}
