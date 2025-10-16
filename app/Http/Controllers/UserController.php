<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Resources\BaseJsonResource;
use App\Repositories\User\UserRepository;
use App\Services\User\UserService;
use Auth;
use Response;

class UserController extends Controller
{

    public function __construct(public UserRepository $userRepository, public UserService $userService)
    {
        //
    }

    function currentUserInfo() {
        $user = $this->userRepository->selectByIdWithRelations(Auth::id());
        $user['permissions'] = $this->userService->selectPermissionList(Auth::id());
        return Response::apiSuccess(new BaseJsonResource(data: $user));
    }
}
