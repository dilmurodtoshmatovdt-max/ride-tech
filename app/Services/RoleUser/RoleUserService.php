<?php

namespace App\Services\RoleUser;

use App\Repositories\RoleUser\RoleUserRepository;

class RoleUserService
{
    public function __construct(public RoleUserRepository $roleUserRepository
    ) {}

    public function addRolesForUser($userId, $roles){
        foreach($roles as $role){
            if(!empty($role['id'])){
                $this->roleUserRepository->insert([
                    'user_id' => $userId,
                    'role_id' => $role['id']
                ]);
            }
        }
    }

}
