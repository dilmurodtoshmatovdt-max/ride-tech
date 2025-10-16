<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\Requests\RoleUser\CreateRoleUserRequest;
use App\Http\Requests\RoleUser\UpdateRoleUserRequest;
use App\Repositories\RoleUser\RoleUserRepository;
use Illuminate\Support\Facades\Response;
use App\Http\Resources\BaseJsonResource;
use DB;

class RoleUserController extends Controller
{

    public function __construct(private RoleUserRepository $roleUserRepository)
    {
        $this->middleware('rbac.verify:'.Permissions::RoleUserList,['only' => ['getAll','getById','getByIdWithLogs']]);
        $this->middleware('rbac.verify:'.Permissions::RoleUserAdd,['only' => ['create']]);
        $this->middleware('rbac.verify:'.Permissions::RoleUserEdit,['only' => ['update']]);
        $this->middleware('rbac.verify:'.Permissions::RoleUserDelete,['only' => ['delete']]);
    }

    public function getAll()
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->roleUserRepository->selectAllWithPagination())
        );
    }
    public function getById(int $id)
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->roleUserRepository->selectByIdWithoutFail($id))
        );
    }
    public function getByIdWithLogs($id)
    {
        return Response::apiSuccess(
            new BaseJsonResource(data: $this->roleUserRepository->selectByIdWithLogs($id))
        );
    }


    public function create(CreateRoleUserRequest $request)
    {
        DB::beginTransaction();
        try {
            $result = Response::apiSuccess(
                new BaseJsonResource(data: $this->roleUserRepository->insert($request->validated()))
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


    public function update(UpdateRoleUserRequest $request, $id)
    {
        DB::beginTransaction();
        try {
            $result = Response::apiSuccess(
                new BaseJsonResource(data: $this->roleUserRepository->update($request->validated(), $id))
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
        $this->roleUserRepository->delete($id);

        return Response::apiSuccess();
    }
}
