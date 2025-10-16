<?php

namespace App\Http\Resources\Crm\Setting;

use App\Http\Resources\BaseJsonResource;

class SettingItemResource extends BaseJsonResource
{
    public function __construct($item)
    {
        $this->data = [
			'id' => $item['id'],
			'name' => $item['name'],
			'value' => $item['value'],
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
			'deleted_at' => $item['deleted_at'],
		];
    }
}
