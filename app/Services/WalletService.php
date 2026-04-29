<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;

use App\Models\Member;
use App\Models\Transaction;
use App\Contracts\Wallet\WalletServiceInterface;

class WalletService implements WalletServiceInterface
{
    public function credit(Member $member, int $amount, string $reason)
    {
        DB::transaction(function () use ($member, $amount, $reason) {
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
    
            return [
                'balance' => $wallet->balance,
                'message' => 'Wallet credited',
                'success' => True
            ];
        });
    }

    public function debit(Member $member, int $amount, string $reason): array
    {
        return DB::transaction(function () use ($member, $amount, $reason) {

            $wallet = $member->wallets()
                ->where('type', 'seedbank')
                ->firstOrFail();

            if ($wallet->balance < $amount) {
                return [
                    'success' => false,
                    'message' => 'Insufficient balance',
                    'data' => null,
                ];
            }

            $wallet->decrement('balance', $amount);

            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount'    => $amount,
                'type'      => 'debit',
                'reason'    => $reason,
            ]);

            return [
                'success' => true,
                'message' => 'Wallet debited',
                'data' => [
                    'balance' => $wallet->balance,
                ],
            ];
        });
    }

    public function getBalance(Member $member): array
    {
        $wallet = $member->wallets()
            ->where('type', 'seedbank')
            ->firstOrFail();

        return [
            'success' => true,
            'message' => 'Wallet balance retrieved',
            'data' => [
                'balance' => $wallet->balance,
            ],
        ];
    }
}