<?php
namespace App\Http\Controllers;

use App\Http\Requests\ApplyRentalRequest;
use App\Contracts\Rental\RentalServiceInterface;
use App\Http\Controllers\Controller;

use App\Models\Plot;

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
        return redirect()->route('rental.create')->with('message', 'Applied successfully!');
    }
}