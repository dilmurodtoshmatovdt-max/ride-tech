<?php

namespace App\Http\Resources\Crm\DoctorType;

use App\Http\Resources\BaseJsonResource;

class DoctorTypeListResource extends BaseJsonResource
{
    public function __construct($data)
    {
        parent::__construct(data: $data);
        $this->data = [];

        foreach ($data as $item){
            $this->data[] = [
				'id' => $item['id'],
				'name' => $item['name'],
				'is_active' => $item['is_active'],
				'created_at' => $item['created_at'],
				'updated_at' => $item['updated_at'],
				'deleted_at' => $item['deleted_at'],
			];
        }
    }
}
