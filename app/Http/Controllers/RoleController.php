<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use App\Http\Resources\BaseJsonResource;
use App\Repositories\Role\RoleRepository;

class RoleController extends Controller
{

    public function __construct(private RoleRepository $roleRepository)
    {

    }

    public function getAll()
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->roleRepository->selectAllWithPagination())
        );
    }
}
