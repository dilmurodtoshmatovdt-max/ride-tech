<?php

namespace App\Http\Resources\Shared\Otp;

use App\Http\Resources\BaseJsonResource;

class OtpMessageItemResource extends BaseJsonResource
{
    public function __construct($otp)
    {

        $this->data = [
            "id" => $otp['id'],
            "user_id" => $otp['user_id'],
            "sms_id" => $otp['sms_id'],
            "otp_type_id" => $otp['otp_type_id'],
            "code_sent_timeout_at" => $otp['code_sent_timeout_at'],
            "code_sent_count" => $otp['code_sent_count'],
            "code_resend_available_at" => $otp['code_resend_available_at'],
            "code_enter_attempt_count" => $otp['code_enter_attempt_count'],
            "code_enter_attempt_available_at" => $otp['code_enter_attempt_available_at'],
            "code_enter_succeeded_at" => $otp['code_enter_succeeded_at'],

            "created_at" => $otp['created_at'],
            "updated_at" => $otp['updated_at'],
            "message" => [
                "id" => $otp['message_id'],
                "uuid" => $otp['message_uuid'],
                "message_type_id" => $otp['message_message_type_id'],
                "message_status_id" => $otp['message_message_status_id'],
                "payload_json" => $otp['message_payload_json'],
                "response_json" => $otp['message_response_json'],

                "created_at" => $otp['message_created_at'],
                "updated_at" => $otp['message_updated_at'],
            ]
        ];
    }
}
