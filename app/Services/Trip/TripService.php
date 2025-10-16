<?php

namespace App\Services\Trip;

use App\Constants\Roles;
use App\Constants\TripStatuses;
use App\Exceptions\AccessForbiddenException;
use App\Exceptions\BusinessLogicException;
use App\Repositories\Car\CarRepository;
use App\Repositories\Trip\TripRepository;
use App\Repositories\User\UserRepository;
use Illuminate\Support\Carbon;
class TripService
{
    public function __construct(
        public TripRepository $tripRepository,
        public CarRepository $carRepository,
        public UserRepository $userRepository
    ) {
    }

    public function getList($requestData, $userId)
    {
        $roles = $this->userRepository->selectRolesByUserId($userId);
        if (in_array(Roles::DRIVER, $roles)) {
            $requestData['driver_id'] = $userId;
            return $this->tripRepository->selectAllWithPaginationAndFilterForDriver($requestData);
        }
        if (in_array(Roles::PASSANGER, $roles)) {
            $requestData['passenger_id'] = $userId;
            return $this->tripRepository->selectAllWithPaginationAndFilterForPassenger($requestData);
        }
    }

    public function insert($requestData, $userId)
    {
        $requestData['passenger_id'] = $userId;
        $requestData['trip_status_id'] = TripStatuses::PENDING;
        return $this->tripRepository->insert($requestData);
    }

    public function update($id, $requestData, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        if (!in_array($trip['trip_status_id'], [TripStatuses::PENDING, TripStatuses::ASSIGNED, TripStatuses::ARRIVED])) {
            throw new BusinessLogicException('Trip status must be on pending, assigned or arrived for change trip');
        }
        if ($trip['passenger_id'] != $userId) {
            throw new AccessForbiddenException();
        }
        return $this->tripRepository->updateByModel($requestData, $trip);
    }

    public function cancel($id, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        if (!in_array($trip['trip_status_id'], [TripStatuses::PENDING, TripStatuses::ASSIGNED, TripStatuses::ARRIVED])) {
            throw new BusinessLogicException('Trip status must be on pending, assigned or arrived for cancel');
        }
        if ($trip['passenger_id'] != $userId) {
            throw new AccessForbiddenException();
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::CANCELED], $trip);
    }

    public function reject($id, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        $this->checkDriverForAccess($trip, $userId);
        if (!in_array($trip['trip_status_id'], [TripStatuses::ASSIGNED, TripStatuses::ARRIVED])) {
            throw new BusinessLogicException('Trip status must be on  assigned or arrived for reject');
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::PENDING], $trip);
    }

    public function assign($id, $userId, $carId)
    {
        $trip = $this->tripRepository->selectById($id);
        $car = $this->carRepository->selectById($carId);
        if ($car['driver_id'] != $userId) {
            throw new AccessForbiddenException('You have\'nt access for this car');
        }
        if ($trip['trip_status_id'] != TripStatuses::PENDING) {
            throw new BusinessLogicException('Trip status must be on pending for assign');
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::ASSIGNED, 'driver_id' => $userId, 'car_id' => $carId], $trip);
    }

    public function arrived($id, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        $this->checkDriverForAccess($trip, $userId);
        if ($trip['trip_status_id'] != TripStatuses::ASSIGNED) {
            throw new BusinessLogicException('Trip status must be on assign for arrived');
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::ARRIVED], $trip);
    }

    public function startTrip($id, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        $this->checkDriverForAccess($trip, $userId);
        if ($trip['trip_status_id'] != TripStatuses::ARRIVED) {
            throw new BusinessLogicException('Trip status must be on arrived for start trip');
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::STARTED, 'started_at' => Carbon::now()->format('Y-m-d H:i:s')], $trip);
    }

    public function finishTrip($id, $userId)
    {
        $trip = $this->tripRepository->selectById($id);
        $this->checkDriverForAccess($trip, $userId);
        if ($trip['trip_status_id'] != TripStatuses::STARTED) {
            throw new BusinessLogicException('Trip status must be on started for finish trip');
        }
        return $this->tripRepository->updateByModel(['trip_status_id' => TripStatuses::COMPLETED, 'finished_at' => Carbon::now()->format('Y-m-d H:i:s')], $trip);
    }

    public function checkDriverForAccess($trip, $driverId)
    {
        if ($trip['driver_id'] != $driverId) {
            throw new AccessForbiddenException();
        }
    }

}
