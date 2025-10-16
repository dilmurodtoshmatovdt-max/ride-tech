<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;

trait QueryFilterableTrait
{
    public function scopeWithQueryFilters(Builder $query, $sortBy = null, $sortField = null)
    {
        $sortBy = request('sortBy', $sortBy ?? 'asc');

        $sortField = request('sortField', $sortField ?? $query->getModel()->getTable() . '.id');

        if (!$sortField && $sortBy && $query->getModel()->getTable() != 'permission_role') {
            $query->orderBy($query->getModel()->getTable() . '.id', $sortBy);
        } else if ($sortBy && $sortField) {
            $query->orderBy($sortField, $sortBy);
        }

        $queryParams = request('filter', []);
        //dd($queryParams);
        foreach ($queryParams as $queryKey => $queryValue) {
            if (is_array($queryValue)) {
                foreach ($queryValue as $key => $value) {
                    $field = $queryKey . '.' . $key;
                    $this->setFilter($field, $value, $query);
                }
            } else {
                $this->setFilter($queryKey, $queryValue, $query);
            }
        }
    }

    public function setFilter($key, $value, Builder $query)
    {
        if (str_ends_with($key, '_at')) {
            $value = Carbon::createFromTimestamp($value)->format('Y-m-d H:i:s');
        }
        if ($key == 'id' || str_ends_with($key, '.id')) {
            $query->where($key, $value);
        } else {
            $query->where($key, 'LIKE', '%' . $value . '%');
        }
    }
}
