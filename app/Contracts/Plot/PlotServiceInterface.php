<?php

namespace App\Contracts\Plot;

use App\Models\Plot\Plot;
use App\Models\Plot\PlotInfection;

use App\Models\Member;
use App\Support\ServiceResult;

interface PlotServiceInterface
{
    public function plantCrop(Plot $plot, Member $user, string $cropType): ServiceResult;

    public function reportInfection(Plot $plot, string $type): ServiceResult;

    public function alertNeighbors(Plot $plot, PlotInfection $infection): ServiceResult;

    public function addFertilizer(Plot $plot, Member $user, string $type) : ServiceResult;

    // // 🌧 AUTOMATION
    // public function generateWateringSchedule(Plot $plot): array;

    // public function generateWinterTasks(Plot $plot): array;
}