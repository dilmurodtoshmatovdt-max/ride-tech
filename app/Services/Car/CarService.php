<?php

namespace App\Services\Car;

use App\Exceptions\AccessForbiddenException;
use App\Exceptions\NotFoundException;
use App\Repositories\Car\CarRepository;
class CarService
{
    public function __construct(
        public CarRepository $carRepository
    ) {
    }

    public function getById($id, $driverId){
        $car = $this->carRepository->selectByIdAndDriverId($id, $driverId);
        if(!$car){
            throw new NotFoundException();
        }
        return $car;
    }

    public function insert($requestData, $userId)
    {
        $requestData['driver_id'] = $userId;
        return $this->carRepository->insert($requestData);
    }

    public function update($id, $requestData, $userId)
    {
        $this->checkForOwn($id, $userId);
        return $this->carRepository->update($requestData, $id);
    }

    public function delete($id, $userId)
    {
        $this->checkForOwn($id, $userId);
        return $this->carRepository->delete($id);
    }

    public function checkForOwn($id, $driverId)
    {
        $car = $this->carRepository->selectById($id);
        if ($car['driver_id'] != $driverId) {
            throw new AccessForbiddenException();
        }
    }
}
