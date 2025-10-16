<?php

namespace App\Repositories\RoleUser;

use App\Models\RoleUser;
use App\Models\User;
use App\Repositories\BaseRepository;
use Str;

class RoleUserRepository extends BaseRepository
{
    public function __construct(public RoleUser $roleUser)
    {
        parent::__construct($roleUser);
    }

    public function deleteRoles($userId)
    {
        $roles = $this->model->where('user_id', $userId)->pluck('id')->toArray();
        $this->deleteMany($roles);
        return true;
    }

    public function insert($data, $primaryKeyOne = 'id', $primaryKeyTwo = null)
    {
        $data['uuid'] = Str::uuid();
        $data['user_type'] = User::class;
        return parent::insert($data);
    }

    public function selectByUserId($userId)
    {
        return $this->model
            ->join('roles', 'role_user.role_id', 'roles.id')
            ->where('role_user.user_id', $userId)
            ->whereNull('role_user.deleted_at')
            ->select('roles.id', 'roles.name')
            ->get();
    }
}
