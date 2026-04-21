<?php

namespace App\Contracts\SeedBank;

interface SeedBankServiceInterface
{
    public function deposit(array $data);

    public function withdraw(array $data);
}