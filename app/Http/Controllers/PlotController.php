<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Plot\Plot;

use App\Http\Requests\Plot\PlantCropRequest;
use App\Http\Requests\Plot\ReportInfectionRequest;

use App\Contracts\Plot\PlotServiceInterface;

class PlotController extends Controller
{
    public function __construct(
        private PlotServiceInterface $service
    ) {}

    public function index()
    {
        $plots = Plot::with([
            'rentals', 
            'infections',
            'rentalApplications.member',
            'currentRental.participants'
            ])->get();
    
        return view('admin.plots.index', compact('plots'));
    }

    public function show(Plot $plot)
    {
        $plot->load([
            'crops.user',
            'infections',
        ]);

        return view('plots.show', compact('plot'));
    }

    public function market()
    {
        $plots = Plot::where('status', 'available')->get();
    
        return view('plots.market', compact('plots'));
    }

    public function ownerView(Plot $plot)
    {
        // $this->authorize('view', $plot);
    
        $plot->load([
            'crops.user',
            'infections',
            'neighbors'
        ]);
    
        return view('plots.owner', compact('plot'));
    }


    public function plant(PlantCropRequest $request, Plot $plot) {
        try {
            $this->service->plantCrop(
                $plot,
                auth()->user(),
                $request->type
            );

            return back()->with('message', 'Crop planted successfully 🌱');

        } catch (\Exception $e) {
            return back()->withErrors($e->getMessage());
        }
    }


    public function reportInfection(ReportInfectionRequest $request, Plot $plot) {
        try {
            $this->service->reportInfection(
                $plot,
                $request->type
            );
    
            return back()->with('message', 'Infection reported 🦠');
    
            } catch (\Exception $e) {
                return back()->withErrors($e->getMessage());
            }
    }

    public function wateringSchedule(Plot $plot) {

    }

}



    // public function map()
    // {
    //     $plots = Plot::get();

    //     return view('plots.map', compact('plots'));
    // }

