<?php

namespace App\Repositories\Permission;

use App\Constants\DocumentStatuses;
use App\Models\Document;
use App\Models\Permission;
use App\Repositories\BaseRepository;
use Closure;
use Request;

class PermissionRepository extends BaseRepository
{
    public function __construct(public Permission $permission)
    {
        parent::__construct($permission);
    }

    public function selectPermissionListForDictionary()
    {
        return $this->permission->all();
    }

    public function selectPermissionList(){
        return $this->model->get()->pluck('name')->toArray();
    }
}
