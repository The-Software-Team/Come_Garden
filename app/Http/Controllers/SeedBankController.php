<?php

namespace App\Http\Controllers;

use App\Models\SeedBatch;
use App\Contracts\SeedBank\SeedBankServiceInterface;

use App\Http\Requests\SeedBank\DepositSeedRequest;
use App\Http\Requests\SeedBank\WithdrawSeedRequest;
use App\Models\Wallet;

class SeedBankController extends Controller
{
    public function __construct(
        private SeedBankServiceInterface $service,
    ) {}
    
    public function profile() {

        $member = auth()->user();

        $seedbank_wallet = Wallet::get()->where('member_id', $member->id)
            ->where('type', 'seedbank')
            ->first();

        $seedbank_credits = $seedbank_wallet->balance;
        $seeds = SeedBatch::where('owner_type', 'inventory')->where('owner_id', $member->id)->get();

        return view('seedbank.index', ['seeds' => $seeds, 'credits' => $seedbank_credits]);
    }

    public function market()
    {
        $batches = SeedBatch::get()->where('owner_type', 'market');
        $seeds = $batches->groupBy('seed_type')->map(function ($group) {
            return [
                'seed_type' => $group->first()->seed_type,
                'quantity' => $group->sum('quantity'),
                'viability' => round($group->avg('viability'), 1),
                'age' => round($group->avg('age'), 1),
                'origin' => $group->pluck('origin')->unique()->values(),
                'created_at' => $group->min('created_at'),
                'updated_at' => $group->max('updated_at'),
                'resources' => $group->pluck('resources')->flatten()->filter()->values(),
            ];
        })->values();

        return view('seedbank.browse', ['seeds' => $seeds]);
    }

    public function depositForm() {
        return view('seedbank.deposit');
    }

    public function store(DepositSeedRequest $request) {
        $data = $request->validated();
        $data['owner_id'] = auth()->user()->id;
        
        $result = $this->service->deposit($data);
        if(!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

    public function withdraw(WithdrawSeedRequest $request) {
        $data = $request->validated();
        $data['member_id'] = auth()->user()->id;

        $result = $this->service->withdraw($data);
        // dd($result); 
        if(!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }
}