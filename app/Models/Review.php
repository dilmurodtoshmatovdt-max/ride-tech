<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Review extends BaseModel
{
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'trip_id',
        'rating',
        'comment'
    ];
    
    protected static function booted()
    {
        static::created(function (Review $review) {
            Cache::tags(["review_driver:{$review->driver_id}"])->flush();
        });

        static::updated(function (Review $review) {
            Cache::tags(["review_driver:{$review->driver_id}"])->flush();
        });

        static::deleted(function (Review $review) {
            Cache::tags(["review_driver:{$review->driver_id}"])->flush();
        });
    }
}
