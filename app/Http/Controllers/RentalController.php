<?php
namespace App\Http\Controllers;

use App\Http\Requests\ApplyRentalRequest;
use App\Contracts\Rental\RentalServiceInterface;
use App\Http\Controllers\Controller;
use App\Models\Season;

use Illuminate\Http\Request;

use App\Models\Plot\Plot;

class RentalController extends Controller
{
    public function __construct(
        private RentalServiceInterface $service
    ) {}

    public function create()
    {
        $plots = Plot::get();

        return view('rental.apply', compact('plots'));
    }  

    public function store(ApplyRentalRequest $request)
    {
        $data = $request->validated();
        $data['member_id'] = auth()->user()->id;

        $result = $this->service->apply($data);

        return redirect()->back()->with('message', "Applied Successfully");
        // return redirect()->route('rental.myapplication')->with('message', 'Applied successfully!');
    }

    public function run()
    {
        $season = Season::where('status', 'active')->first();
    
        if (!$season) {
            return back()->with('message', 'No active season found ');
        }
    
        $plots = Plot::with([
            'rentalApplications.member.wallets',
            'rentals.participants'
        ])->get();
    
        foreach ($plots as $plot) {
            $this->service->rentPlot($plot->id, $season->id);
        }
    
        return redirect()->back()->with('message', 'Rental allocation completed 🌱');
    }

    public function rent(Request $request)
        {
            $request->validate([
                'plot_id' => 'required|exists:plots,id',
            ]);
            
            $plots = Plot::with([
                'rentalApplications',
                'rentalApplications.member',
                'currentRental.participants'
            ])->get();


            $season = Season::where('status', 'active')->first();

            if (!$season) {
                return back()->with('message', 'No active season found ');
            }

            $result = $this->service->rentPlot($request->plot_id, $season->id);

            return redirect()->back()->with('message', 'Plot processed successfully 🌱');
        }

}