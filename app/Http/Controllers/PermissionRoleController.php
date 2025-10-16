<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\Requests\PermissionRole\CreatePermissionRoleRequest;
use App\Http\Requests\PermissionRole\UpdatePermissionRoleRequest;
use App\Repositories\PermissionRole\PermissionRoleRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BaseJsonResource;
use App\Services\PermissionRole\PermissionRoleService;
use DB;

class PermissionRoleController extends Controller
{
    public function __construct(private PermissionRoleRepository $permissionRoleRepository, public PermissionRoleService $permissionRoleService)
    {
        $this->middleware('rbac.verify:'.Permissions::PermissionRoleList,['only' => ['getAll','getById','getByIdWithLogs','getAllByRoleId']]);
        $this->middleware('rbac.verify:'.Permissions::PermissionRoleAdd,['only' => ['create']]);
        $this->middleware('rbac.verify:'.Permissions::PermissionRoleEdit,['only' => ['update']]);
        $this->middleware('rbac.verify:'.Permissions::PermissionRoleDelete,['only' => ['delete']]);
    }

    public function getAll()
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->permissionRoleRepository->selectPaginated())
        );
    }
    public function getById(int $id)
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->permissionRoleRepository->selectByIdWithoutFail($id))
        );
    }
    public function getByIdWithLogs($id)
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->permissionRoleRepository->selectByIdWithLogs($id))
        );
    }

   public function getAllByRoleId($roleId)
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->permissionRoleRepository->selectAllByRoleId($roleId))
        );
    }


    public function create(CreatePermissionRoleRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = Response::apiSuccess(
                new BaseJsonResource(data: $this->permissionRoleService->insert($request->validated()))
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


    public function update(UpdatePermissionRoleRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $result = Response::apiSuccess(
                new BaseJsonResource(data: $this->permissionRoleRepository->update($request->validated(), $id))
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


    public function delete($id)
    {
        $this->permissionRoleRepository->delete($id);

        return Response::apiSuccess();
    }
}
