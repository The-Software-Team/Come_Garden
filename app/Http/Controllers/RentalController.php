<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplyRentalRequest;
use App\Contracts\RentalServiceInterface;
use App\Http\Controllers\Controller;

class RentalController extends Controller
{
    public function __construct(
        private RentalServiceInterface $service
    ) {}

    public function apply(ApplyRentalRequest $request)
    {
        $result = $this->service->apply(
            $request->validated()
        );

        return response()->json($result);
    }
}