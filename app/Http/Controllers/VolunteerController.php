<?php

namespace App\Http\Controllers\Api;
use App\Http\Requests\Request;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateShiftRequest;
use App\Contracts\VolunteerServiceInterface;

class VolunteerController extends Controller
{
    public function __construct(
        private VolunteerServiceInterface $service
    ) {}

    public function createShift(CreateShiftRequest $request)
    {
        return response()->json(
            $this->service->createShift($request->validated())
        );
    }

    public function assign(Request $request)
    {
        return response()->json(
            $this->service->assign($request->all())
        );
    }

    public function complete(Request $request)
    {
        return response()->json(
            $this->service->complete($request->all())
        );
    }

    public function requestSwap(Request $request)
    {
        return response()->json(
            $this->service->requestSwap($request->all())
        );
    }
}