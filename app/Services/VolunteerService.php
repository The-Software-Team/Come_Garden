<?php

namespace App\Services;

use App\Contracts\Volunteer\VolunteerServiceInterface;
use App\Models\Volunteer\Shift;
use Carbon\Carbon;

class VolunteerService implements VolunteerServiceInterface
{
    protected array $shifts = [];
    protected array $memberContribution = [];

    public function createShift(array $data)
    {
        $start = Carbon::parse($data['start_date']);
        $end = (clone $start)->addDays((int) $data['duration_days']);

        Shift::create([
            "start_date" => $start,
            "end_date" => $end,
            "status" => "active"
        ]);
        return ['success' => true];
    }

    public function assign(array $data)
    {
        return [
            'message' => "NO IMPLEMENTAION YET"
        ]; 
   }

    public function complete(array $data)
    {
        return [
            'message' => "NO IMPLEMENTAION YET"
        ]; 
    }

    public function requestSwap(array $data)
    {
        return [
            'message' => "NO IMPLEMENTAION YET"
        ];     
   }


}