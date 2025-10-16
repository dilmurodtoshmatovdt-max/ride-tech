<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\BaseJsonResource;

class ReviewItemResource extends BaseJsonResource
{
    public function __construct($item)
    {
        $this->data = [
			'id' => $item['id'],
			'passenger_id' => $item['passenger_id'],
			'driver_id' => $item['driver_id'],
			'rating' => $item['rating'],
			'comment' => $item['comment'],
			'trip_id' => $item['trip_id'],
			'created_at' => $item['created_at'],
			'updated_at' => $item['updated_at'],
			'deleted_at' => $item['deleted_at'],
		];
    }
}
