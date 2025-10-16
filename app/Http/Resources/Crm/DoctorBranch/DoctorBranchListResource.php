<?php

namespace App\Http\Resources\Crm\DoctorBranch;

use App\Http\Resources\BaseJsonResource;

class DoctorBranchListResource extends BaseJsonResource
{
    public function __construct($data)
    {
        parent::__construct(data: $data);
        $this->data = [];

        foreach ($data as $item){
            $this->data[] = [
				'id' => $item['id'],
				'doctor_id' => $item['doctor_id'],
				'branch_id' => $item['branch_id'],
				'is_active' => $item['is_active'],
				'created_at' => $item['created_at'],
				'updated_at' => $item['updated_at'],
			];
        }
    }
}
