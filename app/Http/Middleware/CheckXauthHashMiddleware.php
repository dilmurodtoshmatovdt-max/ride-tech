<?php

namespace App\Http\Middleware;

use Str;
use Closure;
use Illuminate\Http\Request;
use App\Services\Shared\Auth\AuthService;
use Auth;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Response;

class  CheckXauthHashMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    //public function __construct(public AuthService $authService) {}

    public function handle(Request $request, Closure $next): Response
    {
        $xAuthHash = $request->headers->get('x-auth-hash', null);
        //dd( env('X-AUTH-HASH'));
        if(is_null($$xAuthHash)){
            throw new AccessDeniedException();
        }
        return $next($request);
    }
}
