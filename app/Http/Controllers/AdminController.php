<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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



    public function admin_volunteer() {
        $shifts = Shift::where('status', 'active')->get();
        return view('admin.volunteer', ['shifts' => $shifts]);
    }


}
    