<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreToolRequest;

use App\Http\Controllers\Controller;
use App\Http\Requests\BookToolRequest;
use App\Http\Requests\ReturnToolRequest;

use App\Models\Tool;
use App\Models\Booking;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;


class ToolController extends Controller
{
    public function __construct(
        private ToolLibraryServiceInterface $service
    ) {}

    public function index()
    {
        $tools = Tool::all();

        $bookings = Booking::where('member_id', auth()->id())
            ->whereIn('status', ['active', 'overdue'])
            ->get();

        return view('tools.index', compact('tools', 'bookings'));
    }

    public function store(StoreToolRequest $request)
    {
        $result = $this->service->add_tool($request->validated());

        if (!$result['success']) {
            return back()
                ->withErrors($result['errors'] ?? [])
                ->withInput();
        }

        return redirect()
            ->route('tools.create')
            ->with('message', 'Tool added successfully 🌿');
    }

    public function book(BookToolRequest $request)
    {

        $this->service->book_tool($request->validated());
        return redirect()->back()->with('message', 'Booked Successfully');
    }

    public function return(ReturnToolRequest $request)
    {
        $this->service->return_tool($request->validated());
        return redirect()->back()->with('message', 'Returned Successfully');
    }

    public function reportDamage(Request $request)
    {
        $this->service->reportDamage($request->all());
        return redirect()->back()->with('message', 'Reported Successfully');
    }
}


