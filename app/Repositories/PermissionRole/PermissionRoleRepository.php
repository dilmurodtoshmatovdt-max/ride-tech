<?php

namespace App\Repositories\PermissionRole;

use App\Constants\DocumentStatuses;
use App\Models\Blank;
use App\Models\Document;
use App\Models\PermissionRole;
use App\Repositories\BaseRepository;
use Request;
use DB;

class PermissionRoleRepository extends BaseRepository
{
    public function __construct(public PermissionRole $permissionRole)
    {
        parent::__construct($permissionRole);
    }

    public function selectPaginated($page = null, $perPage = null)
    {

        $perPage = $perPage ?? (int)Request::get('perPage', 15);
        $page = $page ?? (int)Request::get('page', 1);

        $permissionRoles = $this->permissionRole
            ->join('permissions', function ($join) {
                $join->on('permissions.id', 'permission_role.permission_id')
                    ->whereNull('permissions.deleted_at');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', 'permission_role.role_id')
                    ->whereNull('roles.deleted_at');
            })
            ->whereNull('permission_role.deleted_at')
            ->select(
                'permission_role.*',
                DB::raw('GROUP_CONCAT(permissions.display_name SEPARATOR ", ") as permission_display_names'),
                DB::raw('GROUP_CONCAT(permissions.name SEPARATOR ", ") as permission_names'),
                'roles.name as role_name',
                'roles.display_name as role_display_name'
            );
        return $permissionRoles->groupBy('permission_role.role_id')->paginate(perPage: $perPage, page: $page);
    }

    public function selectAllByRoleId($roleId)
    {
        $permissionRoles = $this->permissionRole
            ->join('permissions', function ($join) {
                $join->on('permissions.id', 'permission_role.permission_id')
                    ->whereNull('permissions.deleted_at');
            })
            ->join('roles', function ($join) {
                $join->on('roles.id', 'permission_role.role_id')
                    ->whereNull('roles.deleted_at');
            })
            ->where('permission_role.role_id', $roleId)
            ->whereNull('permission_role.deleted_at')
            ->select(
                'permission_role.*',
                'permissions.display_name as permission_display_names',
                'permissions.name as permission_names',
                'roles.name as role_name',
                'roles.display_name as role_display_name'
            );
        return $permissionRoles->get();
    }

    public function deleteAllByRole($id)
    {
        return $this->permissionRole->where('role_id', $id)->delete();
    }
}
