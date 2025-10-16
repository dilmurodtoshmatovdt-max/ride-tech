<?php

namespace App\Repositories\Review;

use App\Models\Review;
use App\Repositories\BaseRepository;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Request;

class ReviewRepository extends BaseRepository
{
    public function __construct(public Review $review)
    {
        parent::__construct($review);
    }

    public function selectByTripId($tripId)
    {
        return $this->review->where('trip_id', $tripId)->first();
    }

    public function selectByDriverId($driverId)
    {
        $perPage = (int) Request::get('perPage', 15);
        $page = (int) Request::get('page', 1);
        $key = 'reviews:' . md5(json_encode([
            'driver_id' => $driverId,
            'page' => $page,
            'perPage' => $perPage,
        ]));


        return Cache::store('redis')
            ->tags(['review_driver:' . $driverId])
            ->remember($key, config('cache.ttl'), function () use ($driverId, $perPage, $page) {
                return $this->review
                    ->where('driver_id', $driverId)
                    ->orderBy('id', 'desc')
                    ->select(
                        'reviews.*'
                    )->paginate(page: $page, perPage: $perPage);
            });
    }

    public function selectAvgRatingByDriverId($driverId)
    {
        return $this->review
            ->where('driver_id', $driverId)
            ->where('rating', '>', 0)
            ->select(DB::raw('AVG(rating) AS rating'))
            ->first();
    }
}
