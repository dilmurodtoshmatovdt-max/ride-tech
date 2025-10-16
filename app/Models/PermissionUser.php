<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laratrust\Models\Permission as PermissionModel;

class PermissionUser extends Model
{
    use HasFactory;
    public $guarded = [];
}
