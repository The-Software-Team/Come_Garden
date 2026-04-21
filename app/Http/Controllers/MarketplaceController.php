<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Requests\Request;
use App\Http\Requests\CreateListingRequest;
use App\Contracts\MarketplaceServiceInterface;

class MarketplaceController extends Controller
{
    public function __construct(
        private MarketplaceServiceInterface $service
    ) {}

    public function createListing(CreateListingRequest $request)
    {
        return response()->json(
            $this->service->createListing($request->validated())
        );
    }

    public function askQuestion(Request $request)
    {
        return response()->json(
            $this->service->askQuestion($request->all())
        );
    }
}