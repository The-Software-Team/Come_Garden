<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

use App\Contracts\SeedBank\SeedBankServiceInterface;
use App\Models\Volunteer\Shift;

class AdminController extends Controller
{ 
    public function __construct(
        private SeedBankServiceInterface $service,
    ) {}

    public function admin_seedbank()
    {
        $healthAlerts = $this->service->checkSeedHealth()->data['alerts'] ?? [];
        $inventoryAlerts = $this->service->checkInventoryAlerts()->data['alerts'] ?? [];
    
        return view('admin.seedbank', compact('healthAlerts', 'inventoryAlerts'));
    }   

    public function admin_seedbank_store(Request $request) {
        $data = $request->except('_token');
        $result = $this->service->addInventoryItem($data);

        if(!$result->success)
            return redirect()->back()->with('error', $result->message);

        return redirect()->back()->with('message', $result->message);
    }

    public function admin_tool()
    {
        $tools = Tool::withCount(['bookings'])->get();

        return view('admin.tools', compact('tools'));
    }

    public function admin_tool_store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string',
            'usage_status' => 'nullable|string',
            'maintenance_threshold_hours' => 'nullable|integer',
        ]);

        Tool::create([
            'name' => $data['name'],
            'usage_status' => $data['usage_status'] ?? 'low',
            'maintenance_threshold_hours' => $data['maintenance_threshold_hours'] ?? 100,
            'status' => 'available',
        ]);

        return back()->with('message', 'Tool created successfully');
    }

    public function admin_volunteer() {
        $shifts = Shift::where('status', 'active')->get();
        return view('admin.volunteer', ['shifts' => $shifts]);
    }


}
    