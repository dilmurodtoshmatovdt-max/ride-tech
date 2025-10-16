<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class UnauthorizedException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, )
    {
        $this->message = !empty($message) ? __($message) : __('Unauthorized error!');
        parent::__construct($this->message, $code, $previous);
    }
    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::AUTHENTICATION_ERROR,
                message: !empty($this->message) ? __($this->message) : __('Unauthorized error!')
            ),
            401
        );
    }
}
