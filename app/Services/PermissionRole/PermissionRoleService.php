<?php

namespace App\Services\PermissionRole;

use App\Repositories\DocumentItem\DocumentItemRepository;
use App\Repositories\PermissionRole\PermissionRoleRepository;
use App\Repositories\PriceList\PriceListRepository;
use App\Repositories\Service\ServiceRepository;
class PermissionRoleService
{
    public function __construct(
        public DocumentItemRepository $documentItemRepository,
        public PriceListRepository $priceListRepository,
        public ServiceRepository $serviceRepository,
        public PermissionRoleRepository $permissionRoleRepository
    ) {}

    public function insert($requestData)
    {
        $permissions = $requestData['permissions'];

        $this->permissionRoleRepository->deleteAllByRole($requestData['role_id']);

        foreach ($permissions as $permission) {
            $this->permissionRoleRepository->insert([
                'role_id' => $requestData['role_id'],
                'permission_id' => $permission['id']
            ], 'permission_id','role_id');
        }
    }
}
