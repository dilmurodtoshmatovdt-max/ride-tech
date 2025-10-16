<?php

namespace App\Http\Resources\Shared\Auth;

use App\Http\Resources\BaseJsonResource;

class LoginResource extends BaseJsonResource
{
    public function __construct($accessToken, $refreshToken = null) {
        $this->data = [
            'access_token' => $accessToken
        ];

        is_null($refreshToken) ?: $this->data['refresh_token'] = $refreshToken;
    }
}
