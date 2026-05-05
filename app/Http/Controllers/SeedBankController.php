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
        // the seeds and credits of the member.
        $seeds = SeedBatch::where('owner_type', 'inventory')->where('owner_id', auth()->user()->id)->get();

        $seedbank_wallet = Wallet::get()->where('member_id',auth()->user()->id)
            ->where('type', 'seedbank')
            ->first();

        $seedbank_credits = $seedbank_wallet->balance;

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
    
                'origin' => $group->pluck('origin')->unique()->values(),
    
                'age' => round($group->avg('age'), 1),
        
                'created_at' => $group->min('created_at'),
                'updated_at' => $group->max('updated_at'),
    
                // future: merge files
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
            return redirect()->back()->with('message', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

    public function withdraw(WithdrawSeedRequest $request) {
        $data = $request->validated();
        $data['member_id'] = auth()->user()->id;

        $this->service->withdraw($data);
        return redirect()->back()->with('message', "Seed Withdrawn Successfully");
    }
}