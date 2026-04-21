<?php

namespace App\Contracts\Plot;

interface PlotServiceInterface
{
    public function generate(array $data);

    public function reportInfection(array $data);
}