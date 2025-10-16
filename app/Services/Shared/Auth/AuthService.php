<?php

namespace App\Services\Shared\Auth;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use App\Services\Shared\Jwt\JwtService;
use App\Repositories\User\UserRepository;

class AuthService
{
    public function __construct(
        public JwtService $jwtService,
        public UserRepository $userRepository
    ) {
    }

    public function updateLastVisitedAt($userId)
    {
        $this->userRepository->update(['last_visited_at' => Carbon::now()->format('Y-m-d H:i:s')], $userId);
    }
    public function loginByPassword($phoneNumber, $password)
    {
        return Auth::validate(
            [
                'phone_number' => $phoneNumber,
                'password' => $password,
            ]
        );
    }

    public function createAccessToken($userId)
    {
        return $this->jwtService->createAccessToken($userId);
    }
    public function createAccessTokenWithCar($userId, $carId)
    {
        return $this->jwtService->createAccessToken($userId, null, ['car_id' => $carId]);
    }

    public function createRefreshToken($userId)
    {
        return $this->jwtService->createRefreshToken($userId);
    }

    public function createRefreshTokenWithCar($userId, $carId)
    {
        return $this->jwtService->createRefreshToken($userId, null, ['car_id' => $carId]);
    }

    public function createTemporaryToken($userId, $payload = null)
    {
        return $this->jwtService->createTemporaryToken($userId, payload: $payload);
    }

    public function logout()
    {
        return Auth::logout();
    }

    function generatePasswordCode()
    {
        return Str::password(config('auth.password_length'), false, true, false);
    }

    function generatePassword($password = null)
    {
        $password = ($password ?? $this->generatePasswordCode());
        return md5(config('auth.password_salt') . $password . sha1($password));
    }

    function isPasswordCorrect($password, $userPassword)
    {
        return md5(config('auth.password_salt') . $password . sha1($password)) == $userPassword;
    }

}
