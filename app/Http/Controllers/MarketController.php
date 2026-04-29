<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\CreateListingRequest;
use App\Contracts\Marketplace\MarketplaceServiceInterface;

class MarketController extends Controller
{
    public function __construct(
        private MarketplaceServiceInterface $service
    ) {}

    public function store(CreateListingRequest $request)
    {
        $data = $request->validated();
        $data['member_id'] = auth()->user()->id;

        $this->service->createListing($data);

        return redirect()->route('market.create')->with('message', 'Listing created successfully!');
    }
}