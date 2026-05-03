<?php

namespace App\Contracts\Plot;

use App\Models\Plot\Plot;
use App\Models\Plot\PlotInfection;

use App\Models\Member;

interface PlotServiceInterface
{
    public function plantCrop(Plot $plot, Member $user, string $cropType): void;

    public function addFertilizer(Plot $plot, Member $user, string $type): void;

    public function reportInfection(Plot $plot, string $type): void;

    public function alertNeighbors(Plot $plot, PlotInfection $infection): void;

    // 🌧 AUTOMATION
    public function generateWateringSchedule(Plot $plot): array;

    public function generateWinterTasks(Plot $plot): array;
}