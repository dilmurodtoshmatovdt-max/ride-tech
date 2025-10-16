<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Trip\TripAssignRequest;
use App\Http\Requests\Trip\TripFilterRequest;
use App\Http\Requests\Trip\TripInsertRequest;
use App\Http\Requests\Trip\TripUpdateRequest;
use App\Http\Resources\Trip\TripItemResource;
use App\Http\Resources\Trip\TripListResource;
use App\Services\Trip\TripService;
use Auth;
use Response;

class TripController extends Controller
{
    public function __construct(public TripService $tripService)
    {
        $this->middleware('rbac.verify:' . Permissions::TripList, ['only' => ['getAll', 'getById']]);
        $this->middleware('rbac.verify:' . Permissions::TripAdd, ['only' => ['create']]);
        $this->middleware('rbac.verify:' . Permissions::TripEdit, ['only' => ['update']]);
        $this->middleware('rbac.verify:' . Permissions::TripAssign, ['only' => ['assign']]);
        $this->middleware('rbac.verify:' . Permissions::TripArrive, ['only' => ['arrive']]);
        $this->middleware('rbac.verify:' . Permissions::TripStart, ['only' => ['start']]);
        $this->middleware('rbac.verify:' . Permissions::TripFinish, ['only' => ['finish']]);
        $this->middleware('rbac.verify:' . Permissions::TripCancel, ['only' => ['cancel']]);
        $this->middleware('rbac.verify:' . Permissions::TripReject, ['only' => ['reject']]);
    }

    public function getAll(TripFilterRequest $request)
    {
        return Response::apiSuccess(
            new TripListResource(data: $this->tripService->getList($request->validated(), Auth::id()))
        );
    }

    public function getById(int $id)
    {
        return Response::apiSuccess(
            new TripItemResource(item: $this->tripService->tripRepository->selectById($id))
        );
    }


    public function create(TripInsertRequest $request)
    {
        return Response::apiSuccess(
            new TripItemResource(item: $this->tripService->insert($request->validated(), Auth::id()))
        );
    }

    public function update(TripUpdateRequest $request, int $id)
    {
        return Response::apiSuccess(
            new TripItemResource(item: $this->tripService->update($id, $request->validated(), Auth::id()))
        );
    }

    public function cancel(int $id)
    {
        $this->tripService->cancel($id, Auth::id());

        return Response::apiSuccess();
    }

    public function reject(int $id)
    {
        $this->tripService->reject($id, Auth::id());

        return Response::apiSuccess();
    }

    public function assign(TripAssignRequest $request, int $id)
    {
        $requestData = $request->validated();
        $this->tripService->assign($id, Auth::id(), $requestData['car_id']);

        return Response::apiSuccess();
    }

    public function arrive(int $id)
    {
        $this->tripService->arrived($id, Auth::id());

        return Response::apiSuccess();
    }

    public function start(int $id)
    {
        $this->tripService->startTrip($id, Auth::id());

        return Response::apiSuccess();
    }

    public function finish(int $id)
    {
        $this->tripService->finishTrip($id, Auth::id());

        return Response::apiSuccess();
    }

    // public function delete(int $id)
    // {
    //     $this->tripsRepository->delete($id);

    //     return Response::apiSuccess();
    // }
}
