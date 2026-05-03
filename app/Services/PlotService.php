<?php

namespace App\Services;

use App\Models\Member;

use App\Models\Plot\Plot;
use App\Models\Plot\PlotCrop;
use App\Models\Plot\PlotInfection;

use App\Contracts\Plot\PlotServiceInterface;

class PlotService implements PlotServiceInterface
{
    public function plantCrop(Plot $plot, Member $user, string $cropType): void
    {

        // Only rented plots can be planted
        // if ($plot->status !== 'rented') {
        //     throw new \DomainException('Plot is not rented.');
        // }

        $plot->crops()->create([
            'user_id' => $user->id,
            'type' => $cropType,
            'stage' => 'planted', // initial lifecycle stage
        ]);
    }  

    public function addFertilizer(Plot $plot, Member $user, string $type): void
    {
    }

    public function alertNeighbors(Plot $plot, PlotInfection $infection): void
    {
        $neighbors = $plot->neighbors;
    
        foreach ($neighbors as $neighbor) {
            
            $neighbor->infections()->create([
                'type' => "Nearby: {$infection->type}",
                'infection_date' => now(),
                'severity' => 'warning',
            ]);
        }
    }

    public function reportInfection(Plot $plot, string $type): void
    {
        $infection = $plot->infections()->create([
            'type' => $type,
            'infection_date' => now(),
            'severity' => 'medium', // basic default
        ]);
    
        // Alert neighbors
        $this->alertNeighbors($plot, $infection);


    }


    public function generateWateringSchedule(Plot $plot): array
    {
        return [];
    }

    public function generateWinterTasks(Plot $plot): array
    {
        return [];
    }
}