<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Log;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Http\Requests\DepositSeedRequest;

class SeedBankController extends Controller
{
    public function __construct(
        private SeedBankServiceInterface $service
    ) {}

    public function create()
    {
        return view('seedbank');
    }

    public function store(DepositSeedRequest $request)
    {
        $this->service->deposit($request->validated());

        return redirect()->back()->with('success', 'Seeds deposited successfully!');
    }
}