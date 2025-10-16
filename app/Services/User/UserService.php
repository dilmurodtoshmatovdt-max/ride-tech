<?php

namespace App\Services\User;

use App\Constants\Roles;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\User\UserRepository;
use App\Services\RoleUser\RoleUserService;
use Hash;
use Request;

class UserService
{
    public function __construct(
        public RoleUserService $roleUserService,
        public UserRepository $userRepository,
        public PermissionRepository $permissionRepository
    ) {
    }

    public function setPassword($data)
    {
        if (isset($data['password']) && !empty($data['password'])) {
            $data['password'] = Hash::make($data['password'] . env('PASSWORD_SALT'));
        }
        return $data;
    }

    public function selectAllWithPagination($requestData)
    {
        $perPage = (int) Request::get('perPage', 15);
        $page = (int) Request::get('page', 1);
        $search = Request::get('search', false);
        return $this->userRepository->selectAllWithFilter($page, $perPage, $requestData);
    }

    public function selectById($userId)
    {
        return $this->userRepository->selectByIdWithRoles($userId);
    }

    public function selectPermissionList($userId)
    {
        $permissions = $this->userRepository->selectPermissionListByUserId($userId);
        $roles = $this->userRepository->selectRolesByUserId($userId);

        if (in_array(Roles::ADMIN, $roles)) {
            $permissions = $this->permissionRepository->selectPermissionList();
        }
        return $permissions;
    }
    public function insert($requestData)
    {
        $requestData = $this->setPassword($requestData);
        $user = $this->userRepository->insert($requestData);
        $this->roleUserService->roleUserRepository->insert([
            'user_id' => $user['id'],
            'role_id' => $requestData['role_id']
        ]);
        return $user;
    }

    public function update($requestData, $id)
    {
        $user = $this->selectById($id);
        if (isset($requestData['password'])) {
            unset($requestData['password']);
        }
        $user = $this->userRepository->updateByModel($requestData, $user);
        if (isset($requestData['roles']) && count($requestData['roles']) > 0) {
            $this->roleUserService->roleUserRepository->deleteRoles($user['id']);
            $this->roleUserService->addRolesForUser($user->id, $requestData['roles']);
        }
        return $user;
    }
    public function delete($userId)
    {
        $user = $this->selectById($userId);
        $this->userRepository->deleteByModel($user);
    }
}
