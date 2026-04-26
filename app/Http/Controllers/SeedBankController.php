<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Http\Requests\DepositSeedRequest;
use App\Http\Requests\WithdrawSeedRequest;

class SeedBankController extends Controller
{
    public function __construct(
        private SeedBankServiceInterface $service
    ) {}
    # NOTE: DepostSeedRequest and WithdrawSeedRequest are typehints
       
    public function store(DepositSeedRequest $request) {
        $result = $this->service->deposit($request->validated());
        return response()->json($result);
    }

    public function withdraw(WithdrawSeedRequest $request) {
        $result = $this->service->withdraw($request->validated());
        return response()->json($result);
    }
}