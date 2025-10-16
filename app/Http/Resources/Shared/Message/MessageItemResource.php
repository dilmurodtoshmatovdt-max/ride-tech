<?php

namespace App\Http\Resources\Shared\Message;

use App\Http\Resources\BaseJsonResource;

class MessageItemResource extends BaseJsonResource
{
    public function __construct($message)
    {
        $this->data = [
            "id" => $message['id'],
            "message_type_id" => $message['message_type_id'],
            "message_status_id" => $message['message_status_id'],
            "payload_json" => $message['payload_json'],
            "response_json" => $message['response_json'],

            "created_at" => $message['created_at'],
            "updated_at" => $message['updated_at'],
        ];
    }
}
