<?php

namespace App\Constants;


class CacheKeys
{

    /**
     * ---------------------- TAGS ----------------------
     */

    public static function projectIdTag($id)
    {
        return "project_id:{$id}:tag";
    }

    /**
     * ---------------------- KEYS ----------------------
     */

    public static function blacklistTokenKey($token)
    {
        return "blacklisted_token:{$token}";
    }

    public static function OTPKey($phoneNumber, $OTPKey)
    {
        return "user:{$phoneNumber}:otp_key:{$OTPKey}";
    }

    public static function OTPSentCountKey($phoneMobile)
    {
        return "user:{$phoneMobile}:otp_sent_count";
    }

    public static function OTPTryCountKey($phoneMobile)
    {
        return "user:{$phoneMobile}:otp_try_count";
    }

}
