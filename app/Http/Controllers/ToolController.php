<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Http\Requests\ToolLibrary\StoreToolRequest;
use App\Http\Requests\ToolLibrary\BookToolRequest;
use App\Http\Requests\ToolLibrary\ReturnToolRequest;
use App\Http\Requests\ToolLibrary\ReportDamageRequest;

use App\Models\ToolLibrary\Tool;
use App\Models\ToolLibrary\Booking;

use App\Contracts\ToolLibrary\ToolLibraryServiceInterface;
use App\Models\ToolLibrary\ToolWaitlist;

class ToolController extends Controller
{
    public function __construct(
        private ToolLibraryServiceInterface $service
    ) {}

    public function index()
    {
        $tools = Tool::all();

        $bookings = Booking::where('member_id', auth()->user()->id)
            ->whereIn('status', ['active'])
            ->latest()
            ->get()
            ->unique('tool_id') ## enforce it for the sake of factories.
            ->values();

        return view('tools.index', compact('tools', 'bookings'));
    }

    public function admin_index()
    {
        $tools = Tool::withCount(['bookings', 'waitlist'])->get();

        $waitlists = ToolWaitlist::with(['tool', 'member'])
                ->where('status', 'waiting')
                ->orderByDesc('priority_score')
                ->get();

        return view('admin.tools', compact('tools', 'waitlists'));
    }

    public function store(StoreToolRequest $request)
    {
        $data = $request->validated(); 
        $data['member_id'] = auth()->user()->id;

        $result = $this->service->add_tool($data);

        if (!$result->success) 
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

    public function book(BookToolRequest $request)
    {
        $data = $request->validated(); 
        $data['member_id'] = auth()->user()->id;
        
        $result = $this->service->book_tool($data);

        if (!$result->success) 
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }
 
    public function return(ReturnToolRequest $request)
    {
        $data = $request->validated();
        
        $result = $this->service->return_tool($data);
        
        if (!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

   public function reportDamage(ReportDamageRequest $request)
    {
        $data = $request->validated();

        $result = $this->service->reportDamage($data);
        
        if (!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

    public function processWaitlist(Request $request)
    {
        $request->validate([
            'tool_id' => 'required|exists:tools,id'
        ]);
    
        $result = $this->service->processWaitlist($request->tool_id);
    
        if (!$result->success) 
            return redirect()->back()->with('error', $result->message);
    
        return redirect()->back()->with('message', $result->message);
    }

    public function maintainTool(Request $request)
    {
        $this->service->maintainTool((int) $request->tool_id);
    
        return back()->with(
            'message',
            'Tool Maintained Successfully'
        );
    }

    public function scan(string $token)
    {
        $booking = Booking::where('qr_token', $token)
            ->with('tool', 'member')
            ->firstOrFail();
    
        return view('tools.scan', [
            'booking' => $booking
        ]);
    }

    public function processScan(Request $request)
    {
        $token = $request->input('token');
        
        $result = $this->service->processScan($token); 

        if (!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }
}
