<?php

namespace App\Contracts\SeedBank;

use App\Support\ServiceResult;

interface SeedBankServiceInterface
{
    public function deposit(array $data): ServiceResult;

    public function withdraw(array $data): ServiceResult;

    public function addInventoryItem(array $data): ServiceResult;

    public function checkSeedHealth(): ServiceResult;

    public function checkInventoryAlerts(): ServiceResult;
}