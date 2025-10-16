<?php

namespace App\Repositories\User;

use App\Constants\EntityStatuses;
use App\Constants\Roles;
use App\Exceptions\UnknownException;
use App\Models\User;
use App\Repositories\BaseRepository;
use Carbon\Carbon;
use DB;

class UserRepository extends BaseRepository
{
    public function __construct(public User $user)
    {
        parent::__construct($user);
    }

    public function selectAllWithFilter($page, $perPage, $requestData)
    {
        
        $users = $this->user
            ->join('role_user', function ($join) use ($requestData) {
                $join
                    ->on('role_user.user_id', 'users.id')
                    ->when(isset($requestData['role_ids']), fn($query) => $query->whereIn('role_user.role_id', $requestData['role_ids']))
                    ->whereNull('role_user.deleted_at');
            })
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->when(isset($requestData['id']), fn($query) => $query->where('users.id', '=', $requestData['id']))
            ->when(isset($requestData['full_name']), fn($query) => $query->where('users.full_name', 'LIKE', '%' . $requestData['full_name'] . '%'))
            ->when(isset($requestData['birthday']), fn($query) => $query->where('users.birthday', '=', Carbon::parse($requestData['birthday'])->format('Y-m-d')))
            ->when(isset($requestData['login']), fn($query) => $query->where('users.login', 'LIKE', '%' . $requestData['login'] . '%'))
            ->when(isset($requestData['phone_number']), fn($query) => $query->where('users.phone_number', 'LIKE', '%' . $requestData['phone_number'] . '%'))
            ->select(
                'users.*',
                DB::raw('GROUP_CONCAT(roles.name) as role_names'),
                DB::raw('GROUP_CONCAT(role_user.id) as role_id_names')
            );

        if ($requestData['search']) {
            $users = $users->where(function ($query) use ($requestData) {
                $query->where('users.full_name', 'LIKE', '%' . $requestData['search'] . '%')
                    ->orWhere('users.id', 'LIKE', '%' . $requestData['search'] . '%')
                    ->orWhere('users.login', 'LIKE', '%' . $requestData['search'] . '%')
                    ->orWhere('branches.name', 'LIKE', '%' . $requestData['search'] . '%');
            });
        }

        return $users->groupBy('users.id')->withQueryFilters()->simplePaginate($perPage);

    }

    public function selectRolesByUserId($userId)
    {
        return $this->user
            ->join('role_user', function ($join) {
                $join
                    ->on('users.id', 'role_user.user_id')
                    ->whereNull('role_user.deleted_at');
            })
            ->where('users.id', $userId)
            ->select('role_user.role_id as role_id')
            ->get()->pluck('role_id')->toArray();
    }

    public function selectByIdWithRoles($id)
    {
        $user = $this->model
            ->leftJoin('role_user', function ($join) {
                $join
                    ->on('role_user.user_id', 'users.id')
                    ->whereNull('role_user.deleted_at');
            })
            ->leftJoin('roles', 'role_user.role_id', 'roles.id')
            ->leftJoin('branches', 'users.branch_id', 'branches.id')
            ->leftJoin('genders', 'users.gender_id', 'genders.id')
            ->leftJoin('cities', 'users.city_id', 'cities.id')
            ->leftJoin('regions', 'cities.region_id', 'regions.id')
            ->leftJoin('countries', 'regions.country_id', 'countries.id')
            ->where('users.id', $id)
            ->whereNull('users.deleted_at')
            ->groupBy('users.id')
            ->select(
                'users.*',
                'cities.name as city_name',
                'regions.id as region_id',
                'regions.name as region_name',
                'countries.id as country_id',
                'countries.name as country_name',
                'branches.id as branch_id',
                'branches.name as branch_name',
                'genders.name as gender_name',
                DB::raw('GROUP_CONCAT(roles.id) as role_ids'),
                DB::raw('GROUP_CONCAT(roles.name) as role_names'),
            )->first();
        if (!$user) {
            throw new UnknownException(__('Unknown error: User not found!'));
        }
        return $user;
    }
    public function selectPermissionListByUserId($userId)
    {
        return $this->user
            ->join('role_user', function ($join) {
                $join
                    ->on('users.id', 'role_user.user_id')
                    ->whereNull('role_user.deleted_at');
            })
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->join('permission_role', function ($join) {
                $join->on('roles.id', 'permission_role.role_id')
                    ->whereNull('permission_role.deleted_at');
            })
            ->join('permissions', 'permissions.id', 'permission_role.permission_id')
            ->where('users.id', $userId)
            ->whereNull('users.deleted_at')
            ->select(
                'permissions.*',
            )->groupBy('permissions.id')->get()->pluck('name')->toArray();
    }
    public function selectByIdWithRelations($id)
    {
        return $this->user
            ->leftJoin('role_user', function ($join) {
                $join->on('users.id', 'role_user.user_id')
                    ->whereNull('role_user.deleted_at');
            })
            ->leftJoin('roles', function ($join) {
                $join->on('role_user.role_id', 'roles.id')
                    ->whereNull('roles.deleted_at');
            })
            ->where('users.id', $id)
            ->select(
                'users.*',
                DB::raw('GROUP_CONCAT(roles.name) as role_names'),
                DB::raw('GROUP_CONCAT(roles.display_name) as role_display_names')
            )
            ->first();
    }
    public function selectByIdAndUserTypeId($id, $userTypeId)
    {
        $user = $this->model
            ->where('user_type_id', $userTypeId)
            ->where('id', $id)
            ->first();
        if (!$user) {
            throw new UnknownException(__('Unknown error: User not found!'));
        }
        return $user;
    }

    public function selectByPhoneNumberAndLogin($phoneNumber, $login)
    {
        return $this->user
            ->where('phone_number', $phoneNumber)
            ->where('login', $login)
            ->first();
    }

    public function selectByPhoneNumber($phoneNumber)
    {
        return $this->user
            ->where('phone_number', $phoneNumber)
            ->first();
    }
    public function selectByPhoneNumberAndBirthday($phoneNumber, $birthday)
    {
        return $this->user
            ->where('phone_number', $phoneNumber)
            ->where('birth', Carbon::parse($birthday)->format('d.m.Y'))
            ->whereNull('deleted_at')
            ->firstOrFail();
    }
}
