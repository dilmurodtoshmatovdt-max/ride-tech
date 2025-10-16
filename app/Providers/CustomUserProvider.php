<?php

namespace App\Providers;

// use Illuminate\Support\Facades\Gate;

use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Auth\Authenticatable as UserContract;


class CustomUserProvider extends EloquentUserProvider
{

    /**
     * Overrides the framework defaults validate credentials method
     *
     * @param UserContract $user
     * @param array $credentials
     * @return bool
     */
    public function validateCredentials(UserContract $user, array $credentials) {

        if ($user['phone_number'] != $credentials['phone_number']) {
            return false;
        }

        return $this->hasher->check($credentials['password'] . config('auth.password_salt'), $user->getAuthPassword());
    }

}
