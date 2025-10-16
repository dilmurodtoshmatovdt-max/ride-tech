<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Response;

class ValidationException extends BaseException
{
    function __construct($message = "", $code = 0, $previous = null, public $errors = [])
    {
        $this->message = !empty($message) ? __($message) : __('Validation error!');
        parent::__construct($this->message, $code, $previous);
    }
    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::VALIDATION_ERROR,
                message: $this->message,
                errors: $this->errors
            ),
            422
        );
    }
}
