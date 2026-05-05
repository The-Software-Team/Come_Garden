<?php

namespace App\Events\SeedBank;

class InventoryItemAdded
{
    public function __construct(
        public int $ItemId
    ) {}
}