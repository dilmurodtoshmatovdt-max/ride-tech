<?php

namespace App\Models;

use App\Traits\QueryFilterableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class PermissionRole extends Model
{
    use HasFactory, QueryFilterableTrait;

    public function scopeGetOrFail($query, $columns = ['*'])
    {

        $result = $query->get($columns);
        if (empty(count($result))) {
            throw new ModelNotFoundException();
        }
        return $result;
    }
    protected $table= "permission_role";
    public $guarded = [];
}
