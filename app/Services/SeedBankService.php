<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Member;
use App\Models\SeedBatch;
use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Contracts\Wallet\WalletServiceInterface;

class SeedBankService implements SeedBankServiceInterface
{
    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    public function deposit(array $data) : array
    {
        return DB::transaction(function () use ($data) {
            $member = Member::findOrFail($data['member_id']);
            
            $batch = SeedBatch::create([
                'member_id' => $member->id,
                'seed_type' => $data['seed_type'],
                'quantity'  => $data['quantity'],
                'viability' => $data['viability'],
                'origin'    => $data['origin'] ?? null,
                'age'       => $data['age'] ?? null,
                'status'    => 'accepted',
            ]);
    
            $credits = $data['quantity'];
            if ($data['viability'] >= 80) {
                $credits *= 2;
            }
    
            $this->walletService->credit(
                $member,
                $credits,
                'seed_deposit'
            );

            
            # EVENT
            // do this intead
            // SeedDeposited::dispatch(...)->afterCommit();

            # Later we'll add a ServiceResult class
            return [
                'batch_id' => $batch->id,
                'credits_added' => $credits,
                'message' => 'Seed Depoited Successfully',
                'success' => True
                ];
        });
}
    public function withdraw(array $data) : array
    {
         return ['message' => "NO IMPLMENTATION YET"]; 
    }
}