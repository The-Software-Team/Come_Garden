<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;

use App\Http\Controllers\Controller;
use App\Contracts\Plot\PlotServiceInterface;


class PlotController extends Controller
{
    public function __construct(
        private PlotServiceInterface $service
    ) {}

    public function generate(Request $request)
    {
        return response()->json(
            $this->service->generate($request->all())
        );
    }

    public function reportInfection(Request $request)
    {
        return response()->json(
            $this->service->reportInfection($request->all())
        );
    }
}