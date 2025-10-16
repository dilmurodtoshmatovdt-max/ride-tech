<?php

namespace App\Repositories\Car;

use App\Models\Car;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Request;

class CarRepository extends BaseRepository
{
    public function __construct(public Car $car)
    {
        parent::__construct($car);
    }

    public function selectAllWithPaginationByDriverId($driverId)
    {
        $perPage = (int) Request::get('perPage', 15);
        $page = (int) Request::get('page', 1);
        $key = 'cars:' . md5(json_encode([
            'driver_id' => $driverId,
            'page' => $page,
            'perPage' => $perPage,
        ]));

        
        return Cache::store('redis')
            ->tags(['driver:' . $driverId])
            ->remember($key, config('cache.ttl'), function () use ($driverId, $perPage, $page) {
                return $this->car
                    ->where('driver_id', $driverId)
                    ->select(
                        'cars.*'
                    )->paginate(page: $page, perPage: $perPage);
            });

    }

    public function selectByIdAndDriverId($id, $driverId)
    {
        $key = 'cars:' . md5(json_encode([
            'id' => $id,
            'driver_id' => $driverId,
        ]));
        return Cache::store('redis')->tags(['driver:' . $driverId])->remember($key, config('cache.ttl'), function () use ($id, $driverId) {
            return $this->car->where('id', $id)->where('driver_id', $driverId)->first();
        });
    }
}
