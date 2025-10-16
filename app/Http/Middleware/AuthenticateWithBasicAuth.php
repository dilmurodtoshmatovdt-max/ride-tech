<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class AuthenticateWithBasicAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        $basicLogin = env('BASIC_AUTH_LOGIN');
        $basicPassword = env('BASIC_AUTH_PASSWORD');
        header('Cache-Control: no-cache, must-revalidate, max-age=0');

        $login = $request->header('PHP_AUTH_USER');
        $password = $request->header('PHP_AUTH_PW');

        if ($login) {
            session()->put('PHP_AUTH_USER', $login);
        }

        if ($password) {
            session()->put('PHP_AUTH_PW', $password);
        }

        if (session()->get('PHP_AUTH_USER') != $basicLogin || session()->get('PHP_AUTH_PW') != $basicPassword) {

            throw new UnauthorizedHttpException('Basic', 'Invalid credentials.');
            //return new \Illuminate\Http\Response('Invalid credentials.', 401, ['WWW-Authenticate' => 'Basic']);
        }
        return $next($request);

    }
}
