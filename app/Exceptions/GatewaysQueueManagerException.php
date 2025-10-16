<?php

namespace App\Exceptions;

use App\Constants\StatusCodes;
use App\Exceptions\BaseException;
use App\Http\Resources\BaseJsonResource;
use Illuminate\Http\JsonResponse;
use Response;

class GatewaysQueueManagerException extends BaseException
{

    public function __construct(string $message = "", int $code = 0, \Throwable $previous = null, public $errors = null ) {
        $this->message = !empty($message) ? __($message) : __('Gateways queue manager error!');
        parent::__construct($this->message, $code, $previous);
    }

    public function render(): JsonResponse
    {
        return Response::apiError(
            new BaseJsonResource(
                code: StatusCodes::GATEWAYS_QUEUE_MANAGER_ERROR,
                message: $this->message,
                errors: $this->errors
            ),
            200
        );
    }
}
