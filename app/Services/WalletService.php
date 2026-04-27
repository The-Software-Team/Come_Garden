<?php

namespace App\Services;

use App\Models\Member;
use App\Models\Transaction;
use App\Contracts\Wallet\WalletServiceInterface;

class WalletService extends BaseService implements WalletServiceInterface
{
    public function credit(Member $member, int $amount, string $reason)
    {
        $wallet = $member->wallets()
            ->where('type', 'seedbank')
            ->firstOrFail();

        $wallet->increment('balance', $amount);

        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'type'      => 'credit',
            'reason'    => $reason,
        ]);

        return $this->success([
            'balance' => $wallet->balance
        ], 'Wallet credited');
    }

    public function debit(Member $member, int $amount, string $reason)
    {
        $wallet = $member->wallets()
            ->where('type', 'seedbank')
            ->firstOrFail();

        if ($wallet->balance < $amount) {
            return $this->error('Insufficient balance');
        }

        $wallet->decrement('balance', $amount);

        Transaction::create([
            'wallet_id' => $wallet->id,
            'amount'    => $amount,
            'type'      => 'debit',
            'reason'    => $reason,
        ]);

        return $this->success([
            'balance' => $wallet->balance
        ], 'Wallet debited');
    }

    public function getBalance(Member $member)
    {
        $wallet = $member->wallets()
            ->where('type', 'seedbank')
            ->firstOrFail();

        return $this->success([
            'balance' => $wallet->balance
        ], 'Wallet balance retrieved');
    }
}