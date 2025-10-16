<?php

namespace App\Http\Resources\Crm\Employee;

use App\Http\Resources\BaseJsonResource;

class EmployeeListResource extends BaseJsonResource
{
    public function __construct($data)
    {

        $this->data = $data['data'];

        $this->pagination = [
            'currentPage' => $data['current_page'],
            'perPage' => $data['per_page'],
            'hasMorePages' => !is_null($data['next_page_url']) ? true : false,
            'firstItem' => $data['from'],
            'lastItem' => $data['to'],
        ];

    }
}
