<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Facades\Helper\HelperFacade;
use Firebase\JWT\ExpiredException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException as LaravelValidationException;
use Response;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if (app()->bound('sentry')) {
                app('sentry')->captureException($e);
            }

            Log::error($e->getMessage(), HelperFacade::stringifyArrayKeys($e->getTrace()));
        });
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Symfony\Component\HttpFoundation\Response
     *
     * @throws \Throwable
     */
    public function render($request, Throwable $e)
    {

        $data = [];
        $statusCode = 400;
        if ($e instanceof ExpiredException) {
            $data['code'] = StatusCodes::TOKEN_EXPIRED_ERROR;
            $data['message'] = __($e->getMessage());
            $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
            $statusCode = 401;
        } elseif ($e instanceof AuthenticationException) {
            $data['code'] = StatusCodes::AUTHENTICATION_ERROR;
            $data['message'] = __($e->getMessage());
            $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
            $statusCode = 401;
        } elseif ($e instanceof ValidationException) {
            $data['code'] = StatusCodes::VALIDATION_ERROR;
            $data['message'] = __($e->getMessage());
            $data['errors'] = app()->isLocal() ? $e->errors : [];
            $statusCode = 422;
        } elseif ($e instanceof LaravelValidationException) {
            $data['code'] = StatusCodes::VALIDATION_ERROR;
            $data['message'] = __($e->getMessage());
            $data['errors'] = app()->isLocal() ? $e->errors() : [];
            $statusCode = 422;
        } elseif ($e instanceof ModelNotFoundException) {
            $data['code'] = StatusCodes::NOT_FOUND_ERROR;
            $data['message'] = __($e->getMessage());
            $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
            $statusCode = 422;
        } elseif ($e instanceof BaseException) {
            return $e->render();
        } elseif ($e instanceof QueryException) {
            if ($e->errorInfo[1] == 1062) {
                $data['code'] = StatusCodes::VALIDATION_ERROR;
                $data['message'] = __($e->getMessage());
                $data['errors'] = app()->isLocal() ? $e->getTrace() : [];
                $statusCode = 422;
            }
        }

        if (!empty($data)) {
            return Response::apiError($data, $statusCode);
        }

        return $this->prepareJsonResponse($request, $e);

    }

    /**
     * Prepare a JSON response for the given exception.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    protected function prepareJsonResponse($request, Throwable $e)
    {
        return new JsonResponse(
            ['code' => StatusCodes::UNKNOWN_ERROR, 'message' => $e->getMessage(), 'error' => $this->convertExceptionToArray($e)],
            $this->isHttpException($e) ? $e->getStatusCode() : 500,
            $this->isHttpException($e) ? $e->getHeaders() : [],
            JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES
        );
    }
}
