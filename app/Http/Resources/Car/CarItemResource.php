<?php

namespace App\Http\Resources\Car;

use App\Http\Resources\BaseJsonResource;

class CarItemResource extends BaseJsonResource
{
    public function __construct($item)
    {
        $this->data = [
			'id' => $item['id'],
			'driver_id' => $item['driver_id'],
			'brand' => $item['brand'],
			'model' => $item['model'],
			'number' => $item['number'],
			'color' => $item['color'],
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
			'deleted_at' => $item['deleted_at'],
		];
    }
}
