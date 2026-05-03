<?php

namespace App\Contracts\SeedBank;
use App\Models\Member;

interface SeedBankServiceInterface
{
    public function deposit(array $data): array;

    public function withdraw(array $data): array;

    public function addInventoryItem(array $data): void;

    public function checkSeedHealth(): array;

    public function checkInventoryAlerts(): array;
}