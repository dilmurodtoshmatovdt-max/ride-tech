<?php

use App\Http\Middleware\CheckPermissionMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Exceptions\ValidationException;
use App\Facades\Helper\HelperFacade;
use App\Http\Middleware\LastTimeUpdateMiddleware;
use App\Http\Middleware\WithRequestUuidMiddleware;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->prepend(WithRequestUuidMiddleware::class);
        $middleware->prepend(LastTimeUpdateMiddleware::class);
        $middleware->prepend(\BeyondCode\ServerTiming\Middleware\ServerTimingMiddleware::class);

        $middleware->appendToGroup('api', \Rakutentech\LaravelRequestDocs\LaravelRequestDocsMiddleware::class);
        $middleware->alias([
            'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        ]);
        $middleware->alias([
            'rbac.verify' => CheckPermissionMiddleware::class,
        ]);

    })
    ->withExceptions(function (Exceptions $exceptions) {

        $exceptions->render(function (Throwable $e, Request $request) {

            Log::error($e->getMessage(), HelperFacade::stringifyArrayKeys($e->getTrace()));

            $data = [];
            $statusCode = 200;
            if ($e instanceof ExpiredException) {
                $data['code'] = StatusCodes::TOKEN_EXPIRED_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                $statusCode = 200;
            } elseif ($e instanceof AuthenticationException) {
                $data['code'] = StatusCodes::AUTHENTICATION_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                $statusCode = 200;
            } elseif ($e instanceof ThrottleRequestsException) {
                $data['code'] = StatusCodes::TO_MANY_ATTEMPT_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                $statusCode = 200;
            } elseif ($e instanceof ValidationException) {
                $data['code'] = StatusCodes::VALIDATION_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->errors : [];
                $statusCode = 200;
            } elseif ($e instanceof LaravelValidationException) {
                $data['code'] = StatusCodes::VALIDATION_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->errors() : [];
                $statusCode = 200;
            } elseif ($e instanceof ModelNotFoundException) {
                $data['code'] = StatusCodes::NOT_FOUND_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                $statusCode = 200;
            } elseif ($e instanceof BaseException) {
                return $e->render();
            } elseif ($e instanceof QueryException) {
                if ($e->errorInfo[1] == 1062) {
                    $data['code'] = StatusCodes::VALIDATION_ERROR;
                    $data['message'] = __("Dublicate entery") . explode(" ", $e->errorInfo[2])[2] . "," . __("enter another value");
                    $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                    $statusCode = 200;
                }
                if ($e->errorInfo[1] == 1452) {
                    $data['code'] = StatusCodes::VALIDATION_ERROR;
                    $data['message'] = __("This parameter is not in the database! Please select from the directory");
                    $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                    $statusCode = 200;
                }
            }

            if (!empty($data)) {
                return Response::apiError($data, $statusCode);
            }

            return new JsonResponse(
                ['code' => StatusCodes::UNKNOWN_ERROR, 'message' => $e->getMessage(), 'error' => $e->getTrace()],
                $e instanceof HttpExceptionInterface ? $e->getStatusCode() : 200,
                $e instanceof HttpExceptionInterface ? $e->getHeaders() : [],
                JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
            );


        });
    })->create();
