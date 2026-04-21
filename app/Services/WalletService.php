<?php

namespace App\Services;

use App\Contracts\Wallet\WalletServiceInterface;
use App\Models\Wallet;
use App\Models\Transaction;
use App\Models\Member;
use Illuminate\Support\Facades\DB;
use Exception;

class WalletService implements WalletServiceInterface
{
    /**
     * Get member wallets (all types) (main and seedbank)
     */
    public function getWallet(int $memberId)
    {
        return Wallet::where('member_id', $memberId)->get();
    }

    /**
     * Credit wallet
     */
    public function credit(int $memberId, float $amount, string $reason)
    {
        return DB::transaction(function () use ($memberId, $amount, $reason) {

            // 1. Validate
            if ($amount <= 0) {
                throw new Exception("Amount must be positive");
            }

            // 2. Fetch models
            $member = Member::findOrFail($memberId);

            $wallet = Wallet::firstOrCreate(
                ['member_id' => $member->id, 'type' => 'main'],
                ['balance' => 0]
            );

            // 3. Apply logic
            $wallet->balance += $amount;

            // 4. Persist
            $wallet->save();

            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount'    => $amount,
                'type'      => 'credit',
                'reason'    => $reason,
            ]);

            // 5. Events (later)
            // event(new WalletCredited(...));

            return [
                'status' => 'success',
                'balance' => $wallet->balance
            ];
        });
    }

    /**
     * Debit wallet
     */
    public function debit(int $memberId, float $amount, string $reason)
    {
        return DB::transaction(function () use ($memberId, $amount, $reason) {

            // 1. Validate
            if ($amount <= 0) {
                throw new Exception("Amount must be positive");
            }

            // 2. Fetch models
            $member = Member::findOrFail($memberId);

            $wallet = Wallet::where('member_id', $member->id)
                ->where('type', 'main')
                ->first();

            if (!$wallet) {
                throw new Exception("Wallet not found");
            }

            // 3. Apply logic
            if ($wallet->balance < $amount) {
                throw new Exception("Insufficient balance");
            }

            $wallet->balance -= $amount;

            // 4. Persist
            $wallet->save();

            Transaction::create([
                'wallet_id' => $wallet->id,
                'amount'    => -$amount,
                'type'      => 'debit',
                'reason'    => $reason,
            ]);

            // 5. Events (later)
            // event(new WalletDebited(...));

            return [
                'status' => 'success',
                'balance' => $wallet->balance
            ];
        });
    }
}