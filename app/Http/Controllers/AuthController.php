<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Exceptions\UnknownException;
use App\Exceptions\WrongCredentialException;
use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginByPhoneNumberAndPasswordRequest;
use App\Http\Requests\Auth\RegisterRequest;
use App\Http\Requests\Auth\SetOrChangeCarRequest;
use App\Http\Resources\BaseJsonResource;
use App\Http\Resources\Shared\Auth\LoginResource;
use App\Services\Car\CarService;
use App\Services\Shared\Auth\AuthService;
use App\Services\User\UserService;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Response;

class AuthController extends Controller
{

    public function __construct(
        public AuthService $authService,
        public UserService $usersService,
        public CarService $carService
    ) {
        $this->middleware('rbac.verify:' . Permissions::CarDelete, ['only' => ['delete']]);
    }

    public function loginByPassword(LoginByPhoneNumberAndPasswordRequest $request)
    {
        if (!$this->authService->loginByPassword($request->phone_number, $request->password)) {
            throw new WrongCredentialException();
        }

        return Response::apiSuccess(new BaseJsonResource(
            data: [
                'user' => Auth::user(),
            ],
            meta: [
                'access_token' => $this->authService->createAccessToken(Auth::id()),
                'refresh_token' => $this->authService->createRefreshToken(Auth::id()),
                'permissions' => $this->usersService->selectPermissionList(Auth::id())
            ]
        ));
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = Response::apiSuccess(
                new BaseJsonResource(data: $this->usersService->insert($request->validated()))
            );
            if (DB::getPdo()->inTransaction()) {
                DB::commit();
            }
        } catch (\Throwable $th) {
            if (DB::getPdo()->inTransaction()) {
                DB::rollBack();
            }

            throw $th;
        }
        return $result;
    }

    public function refreshToken()
    {
        Auth::refreshToken();

        return Response::apiSuccess(new LoginResource(
            accessToken: $this->authService->createAccessToken(Auth::id()),
            refreshToken: $this->authService->createRefreshToken(Auth::id()),
        ));
    }

    public function logout()
    {
        if (!$this->authService->logout()) {
            throw new UnknownException();
        }

        return Response::apiSuccess();
    }
}
