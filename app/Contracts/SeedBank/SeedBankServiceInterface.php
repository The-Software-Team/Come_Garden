<?php

namespace App\Contracts\SeedBank;
use App\Models\Member;

interface SeedBankServiceInterface
{
    public function deposit(array $data): array;

    public function withdraw(array $data): array;

    public function addInventoryItem(string $name, int $quantity, int $threshold): void;

    public function getUserCredits(Member $user): int;

    public function getAvailableSeeds(): array;

    public function checkInventoryAlerts(): array;
}