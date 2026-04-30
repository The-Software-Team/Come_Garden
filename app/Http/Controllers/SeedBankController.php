<?php

namespace App\Http\Controllers;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Http\Requests\DepositSeedRequest;
use App\Http\Requests\WithdrawSeedRequest;

class SeedBankController extends Controller
{
    public function __construct(
        private SeedBankServiceInterface $service
    ) {}
    # NOTE: DepostSeedRequest and WithdrawSeedRequest are typehints
      
    public function create() {
        return view('seedbank.deposit');
    }

    public function store(DepositSeedRequest $request) {

        $data = $request->validated();
        $data['member_id'] = $request->user()->id;

        $result = $this->service->deposit($data);
        return redirect()->route('seedbank.create')->with('message', 'Deposited Successfully!');
    }

    public function withdraw(WithdrawSeedRequest $request) {
        $result = $this->service->withdraw($request->validated());
    }
}