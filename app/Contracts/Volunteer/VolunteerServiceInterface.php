<?php

namespace App\Contracts\Volunteer;

interface VolunteerServiceInterface
{
    public function createShift(array $data);

    public function assign(array $data);

    public function complete(array $data);

    public function requestSwap(array $data);
}