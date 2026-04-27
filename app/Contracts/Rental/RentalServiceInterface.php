<?php

namespace App\Contracts\Rental;

interface RentalServiceInterface
{
    public function apply(array $data): array;

    public function rentPlot(int $plotId, int $seasonId): array;

    public function endRentals(int $plotId, int $newSeasonId): array;
}


