<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

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
}
    