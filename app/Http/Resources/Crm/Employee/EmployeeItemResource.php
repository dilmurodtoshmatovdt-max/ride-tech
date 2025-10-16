<?php

namespace App\Http\Resources\Crm\Employee;

use App\Http\Resources\BaseJsonResource;

class EmployeeItemResource extends BaseJsonResource
{
    public function __construct($item, $roles, $specializations = [])
    {
        $this->data = [
            'id' => $item['id'],
            "branch_id" => $item['branch_id'],
            "branch_name" => $item['branch_name'],
            "uuid" => $item['uuid'],
            "login" => $item['login'],
            "full_name" => $item['full_name'],
            "first_name" => $item['first_name'],
            "last_name" => $item['last_name'],
            "patronymic_name" => $item['patronymic_name'],
            "phone_number" => $item['phone_number'],
            "address" => $item['address'],
            "passport_series" => $item['passport_series'],
            "birthday" => $item['birthday'],
            "gender_id" => $item['gender_id'],
            "gender_name" => $item['gender_name'],
            "desc" => $item['desc'],
            "city_id" => $item['city_id'],
            "city_name" => $item['city_name'],
            "region_id" => $item['region_id'],
            "region_name" => $item['region_name'],
            "country_id" => $item['country_id'],
            "country_name" => $item['country_name'],
            "params_json" => $item['params_json'],
            'is_active' => $item['is_active'],
            'created_at' => $item['created_at'],
            'updated_at' => $item['updated_at'],
            "last_visited_at" => $item['last_visited_at'],
            "last_document_id" => $item['last_document_id'],
            "roles" => $roles,
            "specializations" => $specializations
        ];
    }
}
