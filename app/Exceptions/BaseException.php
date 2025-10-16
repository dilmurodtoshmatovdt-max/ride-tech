<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Http\Resources\BaseJsonResource;
use Exception;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseException extends Exception
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->message = !empty($message) ? __($message) : __('Unknown error!');
        parent::__construct($this->message, $code, $previous);
    }
    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::UNKNOWN_ERROR,
                message: $this->message
            ),
            200
        );
    }
}
