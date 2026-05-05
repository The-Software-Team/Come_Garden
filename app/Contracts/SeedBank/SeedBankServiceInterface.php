<?php

namespace App\Contracts\SeedBank;

use App\Support\ServiceResult;

interface SeedBankServiceInterface
{
    public function deposit(array $data): ServiceResult;

    public function withdraw(array $data): array;

    public function addInventoryItem(array $data): void;

    public function checkSeedHealth(): array;

    public function checkInventoryAlerts(): array;
}