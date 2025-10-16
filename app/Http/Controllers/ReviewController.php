<?php

namespace App\Http\Controllers;

use App\Constants\Permissions;
use App\Http\Controllers\Controller;
use App\Http\Requests\Review\ReviewInsertRequest;
use App\Http\Resources\Review\ReviewItemResource;
use App\Http\Resources\Review\ReviewListResource;
use App\Services\Review\ReviewService;
use Auth;
use Response;

class ReviewController extends Controller
{
    public function __construct(public ReviewService $reviewService) {
        
        $this->middleware('rbac.verify:' . Permissions::ReviewList, ['only' => ['getById']]);
        $this->middleware('rbac.verify:' . Permissions::ReviewAdd, ['only' => ['create']]);
    }

    public function getById(int $id)
    {
        return Response::apiSuccess(
            new ReviewListResource(data: $this->reviewService->reviewRepository->selectByDriverId($id))
        );
    }


    public function create(ReviewInsertRequest $request)
    {
        return Response::apiSuccess(
            new ReviewItemResource(item: $this->reviewService->insert( $request->validated(), Auth::id()))
        );
    }
}
