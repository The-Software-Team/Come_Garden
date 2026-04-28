<?php

namespace App\Contracts\SeedBank;

interface SeedBankServiceInterface
{
    public function deposit(array $data) : array;

    public function withdraw(array $data) : array;
}