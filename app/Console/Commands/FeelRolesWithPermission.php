<?php

namespace App\Console\Commands;

use App\Constants\Permissions;
use App\Constants\Roles;
use App\Models\PermissionRole;
use App\Repositories\Permission\PermissionRepository;
use App\Repositories\Role\RoleRepository;
use Illuminate\Console\Command;
use Log;

class FeelRolesWithPermission extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'feel-roles-with-permission';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $permissionRepository = app()->make(PermissionRepository::class);
        $roleRepository = app()->make(RoleRepository::class);
        $permissions = $permissionRepository->selectAll()->keyBy('name');
        $roles = $roleRepository->selectAll()->keyBy('id');

        $items = [
            [
                'role' => $roles[Roles::DRIVER],
                'permissions' => [
                    $permissions[Permissions::CarAdd],
                    $permissions[Permissions::CarList],
                    $permissions[Permissions::CarEdit],
                    $permissions[Permissions::CarDelete],
                    $permissions[Permissions::TripList],
                    $permissions[Permissions::TripAssign],
                    $permissions[Permissions::TripArrive],
                    $permissions[Permissions::TripStart],
                    $permissions[Permissions::TripFinish],
                    $permissions[Permissions::TripReject],
                    $permissions[Permissions::ReviewList]
                ]
            ],
            [
                'role' => $roles[Roles::PASSANGER],
                'permissions' => [
                    $permissions[Permissions::TripAdd],
                    $permissions[Permissions::TripEdit],
                    $permissions[Permissions::TripCancel],
                    $permissions[Permissions::TripList],
                    $permissions[Permissions::ReviewAdd]
                ]
            ]
        ];

        foreach ($items as $item) {
            try {
                foreach ($item['permissions'] as $permission) {
                    PermissionRole::updateOrCreate(['permission_id' => $permission['id'], 'role_id'=> $item['role']['id']], [
                        'permission_id' => $permission['id'],
                        'role_id'=> $item['role']['id']
                    ]);
                }
            } catch (\Exception $e) {
                Log::error($e->getMessage());
            }
        }
    }
}
