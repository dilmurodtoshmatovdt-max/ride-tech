<?php

namespace App\Http\Resources\Crm\DoctorBranch;

use App\Http\Resources\BaseJsonResource;

class DoctorBranchItemResource extends BaseJsonResource
{
    public function __construct($item)
    {
        $this->data = [
			'id' => $item['id'],
			'doctor_id' => $item['doctor_id'],
			'branch_id' => $item['branch_id'],
			'is_active' => $item['is_active'],
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
		];
    }
}
