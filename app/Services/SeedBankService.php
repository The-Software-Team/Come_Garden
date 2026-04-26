<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

use App\Models\Member;
use App\Models\SeedBatch;
use App\Models\Transaction;

use App\Contracts\SeedBank\SeedBankServiceInterface;

use App\Events\SeedBank\SeedDeposited;

class SeedBankService implements SeedBankServiceInterface
{
    public function deposit(array $data)
    { 
        return DB::transaction(function () use ($data) {

            // 1. Explicit Validation
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

            // 2. Business rule 
            $credits = $data['quantity'];

            if ($data['viability'] >= 80) {
                $credits *= 2;
            }

            $wallet = $member->wallets()
                ->where('type', 'seedbank')
                ->firstOrFail();

            $wallet->increment('balance', $credits);


            $transaction = Transaction::create([
                'wallet_id' => $wallet->id,
                'amount'    => $credits,
                'type'      => 'credit',
                'reason'    => 'seed_deposit',
            ]);

            // 3. EVENT (can be queued later)
            event(new SeedDeposited(
                memberId: $member->id,
                batchId: $batch->id,
                credits: $credits
            ));

            return [
                'batch_id' => $batch->id,
                'credits_added' => $credits,
                'status' => 'processed'
            ];
        });
    }

    public function withdraw(array $data)
    {
        // Withdrawal logic (not implemented yet)
        return [
            'status' => 'withdrawal not implemented'
        ];
    }
}