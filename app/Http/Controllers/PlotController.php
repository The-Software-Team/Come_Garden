<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Plot;

class PlotController extends Controller
{
    public function index()
    {
        $plots = Plot::with(['rental', 'infections'])->get();
    
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

    public function map()
    {
        $plots = Plot::get();

        return view('plots.map', compact('plots'));
    }


}