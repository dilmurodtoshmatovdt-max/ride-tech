<?php


namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Car extends BaseModel
{
    protected $fillable = ['driver_id', 'brand', 'model', 'number', 'color'];

    protected static function booted()
    {
        static::created(function (Car $car) {
            Cache::tags(["driver:{$car->driver_id}"])->flush();
        });

        static::updated(function (Car $car) {
            Cache::tags(["driver:{$car->driver_id}"])->flush();
        });

        static::deleted(function (Car $car) {
            Cache::tags(["driver:{$car->driver_id}"])->flush();
        });
    }
}
