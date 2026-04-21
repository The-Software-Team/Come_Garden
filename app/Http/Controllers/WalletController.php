<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Contracts\WalletServiceInterface;

class WalletController extends Controller
{
    public function __construct(
        private WalletServiceInterface $service
    ) {}

    public function show($memberId)
    {
        return response()->json(
            $this->service->getWallet($memberId)
        );
    }
}