<?php

namespace App\Events\SeedBank;

class SeedDeposited
{
    public function __construct(
        public int $memberId,
        public int $batchId,
        public int $credits
    ) {}
}