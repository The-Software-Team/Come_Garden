<?php

namespace App\Services;

use App\Models\Member;
use App\Models\SeedBatch;
use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Contracts\Wallet\WalletServiceInterface;

class SeedBankService extends BaseService implements SeedBankServiceInterface
{
    public function __construct(
        private WalletServiceInterface $walletService
    ) {}

    public function deposit(array $data)
    {
        return $this->transaction(function () use ($data) {

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

            // Business rule
            $credits = $data['quantity'];
            if ($data['viability'] >= 80) {
                $credits *= 2;
            }

            $walletResult = $this->walletService->credit(
                $member,
                $credits,
                'seed_deposit'
            );

            if (!$walletResult['success']) {
                return $walletResult;
            }

            // Event 
            event(new \App\Events\SeedBank\SeedDeposited(
                $member->id,
                $batch->id,
                $credits
            ));

            return $this->success([
                'batch_id' => $batch->id,
                'credits_added' => $credits
            ], 'Seeds deposited successfully');
        });
    }

    public function withdraw(array $data)
    { pass; }
}