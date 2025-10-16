<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;

class Trip extends BaseModel
{
    protected $fillable = [
        'passenger_id',
        'driver_id',
        'trip_status_id',
        'car_id',
        'from_address',
        'to_address',
        'preferences',
        'status',
        'price',
        'started_at',
        'finished_at'
    ];

    protected $casts = ['started_at' => 'datetime', 'finished_at' => 'datetime'];

    public function passenger()
    {
        return $this->belongsTo(User::class, 'passenger_id');
    }
    public function driver()
    {
        return $this->belongsTo(User::class, 'driver_id');
    }
    public function car()
    {
        return $this->belongsTo(Car::class);
    }

    protected static function booted()
    {
        static::created(fn() => Cache::tags(['trips'])->flush());
        static::updated(fn() => Cache::tags(['trips'])->flush());
        static::deleted(fn() => Cache::tags(['trips'])->flush());
    }
}
