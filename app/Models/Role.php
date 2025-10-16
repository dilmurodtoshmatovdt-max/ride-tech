<?php

namespace App\Models;

use App\Traits\QueryFilterableTrait;
use Laratrust\Models\Role as RoleModel;

class Role extends RoleModel
{
    use QueryFilterableTrait;
    public $guarded = [];
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}
