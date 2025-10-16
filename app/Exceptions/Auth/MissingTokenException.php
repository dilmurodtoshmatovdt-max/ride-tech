<?php

namespace App\Exceptions\Auth;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Support\Facades\Response;
use Symfony\Component\HttpFoundation\JsonResponse;

class MissingTokenException extends BaseException
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null) {
        $this->message = !empty($message) ? __($message) : __('Wrong credentials error: Token is missing!');
        parent::__construct($this->message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::WRONG_CREDENTIALS_ERROR,
                message:$this->message
            ),
            401
        );
    }
}
