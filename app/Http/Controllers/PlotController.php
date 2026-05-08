<?php

namespace App\Http\Controllers;
use App\Http\Controllers\Controller;

use App\Models\Plot\Plot;

use App\Http\Requests\Plot\PlantCropRequest;
use App\Http\Requests\Plot\ReportInfectionRequest;
use App\Http\Requests\Plot\AddFertilizerRequest;

use App\Contracts\Plot\PlotServiceInterface;

class PlotController extends Controller
{
    public function __construct(
        private PlotServiceInterface $service
    ) {}

    public function admin_index()
    {
        $plots = Plot::with([
            'rentals',
            'infections',
            'rentalApplications' => function ($query) {
                $query->where('status', 'pending');
            },
            'rentalApplications.member',
            'currentRental.participants'
        ])->get();
    
        return view('admin.plots.index', compact('plots'));
    }

    public function market()
    {
        $plots = Plot::withCount('neighbors')->where('status', 'available')->get();

        return view('plots.market', compact('plots'));
    }

    public function show(Plot $plot)
    {
        $plot->load([
            'crops.user',
            'infections',
        ]);

        return view('plots.show', compact('plot'));
    }

    public function ownerView(Plot $plot)
    {
        $plot->load([
            'crops.user',
            'infections',
            'neighbors'
        ]);
    
        return view('plots.owner', compact('plot'));
    }


    public function plant(PlantCropRequest $request, Plot $plot) {

            $result = $this->service->plantCrop($plot, auth()->user(), $request->type);

            if (!$result->success)
                return redirect()->back()->with('error', "Planting failed");

            return redirect()->back()->with('message', 'Crop planted successfully');
    }


    public function reportInfection(ReportInfectionRequest $request, Plot $plot) {

            $result = $this->service->reportInfection($plot, $request->type);
            
            if (!$result->success)
                return redirect()->back()->with('error', 'Reporting failed');

            return back()->with('message', 'Infection reported');
    }


 
    public function fertilize(AddFertilizerRequest $request, Plot $plot)
    {
        $result = $this->service->addFertilizer(
            $plot,
            auth()->user(),
            $request->fertilizer_type
        );

        if (!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

}
