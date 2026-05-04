<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tool;

use App\Contracts\SeedBank\SeedBankServiceInterface;

class AdminController extends Controller
{ 
    public function __construct(
        private SeedBankServiceInterface $service,
    ) {}

    public function admin_seedbank() {
        $healthAlerts = $this->service->checkSeedHealth();
        $inventoryAlerts = $this->service->checkInventoryAlerts();

        return view('admin.seedbank', compact('healthAlerts', 'inventoryAlerts'));

    }

    public function admin_seedbank_store(Request $request) {
        $data = $request->except('_token');
        $this->service->addInventoryItem($data);

        return redirect()->back()->with('message', 'Inventory Item Added Successfully');
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

}
    