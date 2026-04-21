<?php

namespace App\Contracts\Wallet;

interface WalletServiceInterface
{
    public function getWallet(int $memberId);

    public function credit(int $memberId, float $amount, string $reason);

    public function debit(int $memberId, float $amount, string $reason);
}