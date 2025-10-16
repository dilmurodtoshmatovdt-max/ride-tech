<?php

namespace App\Exceptions\User;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\JsonResponse;
use Response;

class CannotCreateUserException extends BaseException
{
    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null)
    {
        $this->message = !empty($message) ? __($message) : __("Wrong credentials error: Can't create user with provided credentials!");
        parent::__construct($this->message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::WRONG_CREDENTIALS_ERROR,
                message: $this->message
            )
        );
    }
}
