<?php

namespace App\Repositories\Trip;

use App\Constants\TripStatuses;
use App\Models\Trip;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Request;

class TripRepository extends BaseRepository
{
    public function __construct(public Trip $trip)
    {
        parent::__construct($trip);
    }

    public function selectAllWithPaginationAndFilterForPassenger($requestData)
    {
        $perPage = (int) Request::get('perPage', 15);
        $page = (int) Request::get('page', 1);

        $key = 'trips:' . md5(json_encode([
            'status_ids' => $requestData['trip_status_ids'] ?? null,
            'passenger_id' => $requestData['passenger_id'] ?? null,
            'date' => $requestData['date'] ?? null,
            'page' => $page,
            'perPage' => $perPage,
        ]));

        return Cache::store('redis')->tags(['trips'])->remember($key, config('cache.ttl'), function () use ($requestData, $perPage, $page) {
            return $this->trip
                ->join('trip_statuses', function ($join) use ($requestData) {
                    $join->on('trips.trip_status_id', 'trip_statuses.id')
                        ->when(isset($requestData['trip_status_ids']), fn($join) => $join->whereIn('trip_statuses.id', $requestData['trip_status_ids']));
                })
                ->join('users as passengers', function ($join) use ($requestData) {
                    $join->on('trips.passenger_id', 'passengers.id')
                        ->where('passengers.id', $requestData['passenger_id']);
                })
                ->leftJoin('users as drivers', 'trips.driver_id', '=', 'drivers.id')
                ->leftJoin('cars', 'trips.car_id', 'cars.id')
                ->when(isset($requestData['date']), fn($join) => $join->whereRaw('DATE(trips.created_at) ="' . $requestData['date'] . '"'))
                ->orderBy('trips.id', 'desc')
                ->select(
                    'trips.*',
                    'trip_statuses.name as trip_status_name',
                    'drivers.name as driver_name',
                    'cars.brand as car_brand',
                    'cars.model as car_model',
                    'cars.number as car_number',
                    'cars.color as car_color',
                )->paginate(page: $page, perPage: $perPage);
        });


    }

    public function selectAllWithPaginationAndFilterForDriver($requestData)
    {
        $perPage = (int) Request::get('perPage', 15);
        $page = (int) Request::get('page', 1);
        $key = 'trips:' . md5(json_encode([
            'status_ids' => $requestData['trip_status_ids'] ?? null,
            'driver_id' => $requestData['driver_id'] ?? null,
            'date' => $requestData['date'] ?? null,
            'page' => $page,
            'perPage' => $perPage,
        ]));

        return Cache::store('redis')->tags(['trips'])->remember($key, config('cache.ttl'), function () use ($requestData, $perPage, $page) {
            $trips = $this->trip
                ->join('trip_statuses', function ($join) use ($requestData) {
                    $join->on('trips.trip_status_id', 'trip_statuses.id')
                        ->when(isset($requestData['trip_status_ids']), fn($join) => $join->whereIn('trip_statuses.id', $requestData['trip_status_ids']));
                })
                ->join('users as passengers', 'trips.passenger_id', 'passengers.id');

            if (!empty($requestData['trip_status_ids']) && !in_array(TripStatuses::PENDING, $requestData['trip_status_ids'])) {
                $trips->join('users as drivers', function ($join) use ($requestData) {
                    $join->on('trips.driver_id', 'drivers.id')
                        ->where('drivers.id', $requestData['driver_id']);
                });
            } else {
                $trips->leftJoin('users as drivers', 'trips.driver_id', '=', 'drivers.id')
                    ->whereNull('trips.driver_id')
                    ->where('trips.trip_status_id', '!=', TripStatuses::CANCELED);
            }

            return $trips
                ->leftJoin('cars', 'trips.car_id', 'cars.id')
                ->when(isset($requestData['date']), fn($join) => $join->whereRaw('DATE(trips.created_at) ="' . $requestData['date'] . '"'))
                ->orderBy('trips.id', 'desc')
                ->select(
                    'trips.*',
                    'trip_statuses.name as trip_status_name',
                    'drivers.name as driver_name',
                    'cars.brand as car_brand',
                    'cars.model as car_model',
                    'cars.number as car_number',
                    'cars.color as car_color',
                )
                ->paginate(page: $page, perPage: $perPage);
        });
    }
}
