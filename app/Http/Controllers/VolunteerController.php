<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Volunteer\Shift;
use App\Contracts\Volunteer\VolunteerServiceInterface;
use App\Models\Volunteer\Assignment;

class VolunteerController extends Controller
{
    public function __construct(
        protected VolunteerServiceInterface $service
    ) {}


    public function index() {
        $assignments = Assignment::where('member_id', auth()->user()->id)->get();
        return view('volunteer.index', ['assignments' => $assignments]);
    }

    public function adminIndex() {
        $shifts = Shift::where('status', 'active')->get();
        return view('admin.volunteer', ['shifts' => $shifts]);
    }

    public function createShift(Request $request)
    {
        $this->service->createShift($request->all());

        return redirect()->back()->with('success', 'Shift created successfully.');
    }

    public function assign(Request $request)
    {
        $this->service->assign($request->all());

        return redirect()->back()->with('success', '❌ No Implementation yet.');
    }

    public function complete(Request $request)
    {
        $this->service->complete($request->all());

        return redirect()->back()->with('success', '❌ No Implementation yet.');
    }

    public function requestSwap(Request $request)
    {
        $this->service->requestSwap($request->all());

        return redirect()->back()->with('success', '❌ No Implementation yet.');
    }
}