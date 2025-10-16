<?php

namespace App\Models;

use App\Constants\EntityLogTypes;
use App\Traits\QueryFilterableTrait;
use App\Traits\WithEntityLogTypesTrait;
use Auth;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Builder;
use Request;

class BaseModel extends Model
{
    use HasFactory, QueryFilterableTrait, SoftDeletes;

    public function scopeGetOrFail($query, $columns = ['*'])
    {

        $result = $query->get($columns);
        if (empty(count($result))) {
            throw new ModelNotFoundException();
        }
        return $result;
    }

    
    protected static function boot()
    {
        parent::boot();

        $userId = 1;

        try {
            $userId = Auth::id();
        } catch (\Throwable $th) {
            
        }

    }

}
