<?php

namespace App\Contracts\Wallet;
use App\Models\Member;

interface WalletServiceInterface
{
    public function getBalance(Member $member);

    public function credit(Member $member, int $amount, string $reason);

    public function debit(Member $member, int $amount, string $reason);
}