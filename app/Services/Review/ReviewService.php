<?php

namespace App\Services\Review;

use App\Constants\TripStatuses;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\BusinessLogicException;
use App\Repositories\Review\ReviewRepository;
use App\Repositories\Trip\TripRepository;
use App\Repositories\User\UserRepository;
class ReviewService
{
    public function __construct(
        public ReviewRepository $reviewRepository,
        public UserRepository $userRepository,
        public TripRepository $tripRepository
    ) {
    }

    public function insert($requestData, $userId)
    {
        $trip = $this->tripRepository->selectById($requestData['trip_id']);
        if ($trip['passenger_id'] != $userId) {
            throw new AccessForbiddenException();
        }
        if ($trip['trip_status_id'] != TripStatuses::COMPLETED) {
            throw new BusinessLogicException('Trip status must be finished for add review');
        }
        $review = $this->reviewRepository->selectByTripId($requestData['trip_id']);
        if ($review) {
            throw new BusinessLogicException('You already add review for this trip');
        }
        $review = $this->reviewRepository->insert([
            'passenger_id' => $userId,
            'driver_id' => $trip['driver_id'],
            'rating' => $requestData['rating'] ?? null,
            'comment' => $requestData['comment'],
            'trip_id' => $requestData['trip_id']
        ]);
        $this->updateDriverRating($trip['driver_id']);
        return $review;
    }

    public function updateDriverRating($driverId)
    {
        $rating = $this->reviewRepository->selectAvgRatingByDriverId($driverId);
        $this->userRepository->update(['rating' => round($rating['rating'], 1)], $driverId);
    }
}
