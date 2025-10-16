<?php

namespace App\Http\Resources\Shared\Otp;

use App\Http\Resources\BaseJsonResource;

class OtpItemResource extends BaseJsonResource
{
    public function __construct($otp)
    {

        $this->data = [
            "id" => $otp['id'],
            "user_id" => $otp['user_id'],
            "otp_type_id" => $otp['otp_type_id'],
            "code_sent_timeout_at" => $otp['code_sent_timeout_at'],
            "code_sent_count" => $otp['code_sent_count'],
            "code_resend_available_at" => $otp['code_resend_available_at'],
            "code_enter_attempt_count" => $otp['code_enter_attempt_count'],
            "code_enter_attempt_available_at" => $otp['code_enter_attempt_available_at'],
            "code_enter_succeeded_at" => $otp['code_enter_succeeded_at'],

            "created_at" => $otp['created_at'],
            "updated_at" => $otp['updated_at'],
        ];
    }
}
