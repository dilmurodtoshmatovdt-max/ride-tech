<?php

namespace App\Http\Middleware;

use App\Services\Shared\Auth\AuthService;
use Auth;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LastTimeUpdateMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */

    public function __construct(public AuthService $authService) {}

    public function handle(Request $request, Closure $next): Response
    {
        try {
            if(Auth::isUserHasToken()){
                $this->authService->updateLastVisitedAt(Auth::id());
            }
        } catch (\Throwable $th) {
            //throw $th;
        }
       
        return $next($request);
    }
}
