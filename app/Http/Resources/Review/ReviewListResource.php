<?php

namespace App\Http\Resources\Review;

use App\Http\Resources\BaseJsonResource;

class ReviewListResource extends BaseJsonResource
{
    public function __construct($data)
    {
        parent::__construct(data: $data);
        $this->data = [];

        foreach ($data as $item){
            $this->data[] = [
				'id' => $item['id'],
				'rating' => $item['rating'],
				'comment' => $item['comment'],
				'created_at' => $item['created_at']
			];
        }
    }
}
