<?php

namespace App\Contracts\Rental;

interface RentalServiceInterface
{
    public function apply(array $data);

    public function approve(int $applicationId);

    public function processWaitlist(int $plotId);
}