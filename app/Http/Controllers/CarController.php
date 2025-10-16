<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Car\CarInsertRequest;
use App\Http\Requests\Car\CarUpdateRequest;
use App\Http\Resources\Car\CarItemResource;
use App\Http\Resources\Car\CarListResource;
use App\Services\Car\CarService;
use Auth;
use Response;

class CarController extends Controller
{
    public function __construct(public CarService $carService)
    {
        $this->middleware('rbac.verify:' . Permissions::CarList, ['only' => ['getAll', 'getById', 'getByIdWithLogs', 'permissionListFotDictionary']]);
        $this->middleware('rbac.verify:' . Permissions::CarAdd, ['only' => ['create']]);
        $this->middleware('rbac.verify:' . Permissions::CarEdit, ['only' => ['update']]);
        $this->middleware('rbac.verify:' . Permissions::CarDelete, ['only' => ['delete']]);
    }

    public function getAll()
    {
        return Response::apiSuccess(
            new CarListResource(data: $this->carService->carRepository->selectAllWithPaginationByDriverId(Auth::id()))
        );
    }

    public function getById(int $id)
    {
        return Response::apiSuccess(
            new CarItemResource(item: $this->carService->getById($id, Auth::id()))
        );
    }


    public function create(CarInsertRequest $request)
    {
        return Response::apiSuccess(
            new CarItemResource(item: $this->carService->insert($request->validated(), Auth::id()))
        );
    }


    public function update(CarUpdateRequest $request, int $id)
    {
        return Response::apiSuccess(
            new CarItemResource(item: $this->carService->update($id, $request->validated(), Auth::id()))
        );
    }


    public function delete(int $id)
    {
        $this->carService->delete($id, Auth::id());

        return Response::apiSuccess();
    }
}
