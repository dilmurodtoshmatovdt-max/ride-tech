<?php

namespace App\Http\Resources\Trip;

use App\Http\Resources\BaseJsonResource;

class TripItemResource extends BaseJsonResource
{
    public function __construct($item)
    {
        $this->data = [
			'id' => $item['id'],
			'passenger_id' => $item['passenger_id'],
			'driver_id' => $item['driver_id'],
			'car_id' => $item['car_id'],
			'trip_status_id' => $item['trip_status_id'],
			'from_address' => $item['from_address'],
			'to_address' => $item['to_address'],
			'preferences' => $item['preferences'],
			'started_at' => $item['started_at'],
			'finished_at' => $item['finished_at'],
			'price' => $item['price'],
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
			'deleted_at' => $item['deleted_at'],
		];
    }
}
