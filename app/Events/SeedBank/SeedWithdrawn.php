<?php

namespace App\Events\SeedBank;

class SeedWithdrawn
{
    public function __construct(
        public int $quantity
    ) {}
}