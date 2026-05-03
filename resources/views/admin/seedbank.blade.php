@extends('layouts.app')

@section('title', 'Seed Bank Admin')

@section('styles')
@vite([
'resources/css/domain/seedbank.css'
])
@endsection

@section('dynamics')
<script>
function showAlert(alert) {

    let content = '';

    if (alert.type === 'health') {

        content = `
            <div class="details-header">
                <h2>${alert.seed_type}</h2>
                <span class="badge ${alert.status === 'EXPIRED' ? 'danger' : 'warning'}">
                    ${alert.status}
                </span>
            </div>

            <div class="grid-info">

                <div class="info-box">
                    <div class="info-label">Status</div>
                    <div class="info-value">${alert.status}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Batch ID</div>
                    <div class="info-value">${alert.batch_id}</div>
                </div>

            </div>
        `;
    }

    if (alert.type === 'inventory') {

        content = `
            <div class="details-header">
                <h2>${alert.name}</h2>
                <span class="badge warning">
                    REORDER
                </span>
            </div>

            <div class="grid-info">

                <div class="info-box">
                    <div class="info-label">Current Quantity</div>
                    <div class="info-value">${alert.quantity}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Status</div>
                    <div class="info-value">Reorder Required</div>
                </div>

            </div>
        `;
    }

    document.getElementById('alert-details').innerHTML = content;
}

function openInventoryModal() {
    document.getElementById('inventory-modal').classList.remove('hidden');
}

function closeInventoryModal() {
    document.getElementById('inventory-modal').classList.add('hidden');
}
</script>
@endsection

@section('content')
@if(session('message'))
    <div class="alert alert--success">
        {{ session('message') }}
    </div>
@endif


<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: Alerts List --}}
    <div class="col-span-4">
        <h2>🚨 Alerts</h2>
        <button class="btn" onclick="openInventoryModal()">
            + Add Item
        </button>

        <div class="seed-list">

            {{-- Seed Health --}}
            @foreach($healthAlerts as $alert)
                <div class="seed-list-item"
                     onclick='showAlert(@json(array_merge($alert, ["type" => "health"])))'>

                    🌱 {{ $alert['seed_type'] }}
                    <small>({{ $alert['status'] }})</small>
                </div>
            @endforeach

            {{-- Inventory --}}
            @foreach($inventoryAlerts as $item)
                <div class="seed-list-item"
                     onclick='showAlert(@json(array_merge($item, ["type" => "inventory"])))'>

                    📦 {{ $item['name'] }}
                    <small>(REORDER)</small>
                </div>
            @endforeach

        </div>
    </div>

    {{-- RIGHT: Details --}}
    <div class="col-span-8">
        <div class="panel">

            <div id="alert-details">
                <div class="empty-state">
                    <h3>Select an Alert</h3>
                    <p>Choose an alert from the left to inspect details.</p>
                </div>
            </div>

        </div>
    </div>

<div id="inventory-modal" class="modal hidden">

    <div class="modal-content">

        <div class="modal-header">
            <h3>📦 Add Inventory Item</h3>
            <span class="close" onclick="closeInventoryModal()">&times;</span>
        </div>

        <form method="POST" action="{{ route('admin_seedbank.store') }}">
            @csrf

            <input class="input" name="name" placeholder="Item name" required>
            <input class="input" name="quantity" type="number" placeholder="Quantity" required>
            <input class="input" name="threshold" type="number" placeholder="Reorder threshold" required>

            <button class="btn" type="submit">
                Save Item
            </button>
        </form>

    </div>

</div>
</div>

@endsection