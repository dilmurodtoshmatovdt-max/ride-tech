<?php

namespace App\Models;

use App\Traits\QueryFilterableTrait;
use Laratrust\Models\Permission as PermissionModel;

class Permission extends PermissionModel
{
    use QueryFilterableTrait;
    public $guarded = [];
    protected $fillable = [
        'name',
        'display_name',
        'description',
    ];
}
