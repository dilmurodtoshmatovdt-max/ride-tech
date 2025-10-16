<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\JsonResponse;
use Response;

class UnknownException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, string $entityName = null)
    {
        $this->message = !empty($message) ? __($message).(!empty($entityName) ? " ". __($entityName) : "") : __('Unknown error!');
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
