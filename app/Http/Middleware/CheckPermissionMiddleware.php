<?php

namespace App\Http\Middleware;

use Auth;
use Closure;
use App\Exceptions\AccessForbiddenException;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermissionMiddleware
{
    public function handle(Request $request, Closure $next,string $permission): Response
    {

        if (!\Auth::user()->ability('sadmin',$permission)) {
            throw new AccessForbiddenException();
        }

        return $next($request);
    }

}
