<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class NotFoundException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, )
    {
        $this->message = !empty($message) ? __($message) : __('Not found!');
        parent::__construct($this->message, $code, $previous);
    }
    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::NOT_FOUND_ERROR,
                message: $this->message,
            ),
            status: 404
        );
    }
}
