@extends('layouts.app')

@section('page-title', 'Seed Bank Admin')

@section('content')

<div class="grid grid-cols-2 gap-6">

    {{-- Alerts --}}
    <div class="bg-white p-4 shadow rounded">
        <h3 class="font-bold">Seed Health Alerts</h3>

        @foreach($alerts['alerts'] ?? [] as $alert)
            <div class="text-red-600">
                {{ is_array($alert) ? $alert[0] . ' - ' . $alert[1] : $alert }}
            </div>
        @endforeach
    </div>

    {{-- Inventory alerts --}}
    <div class="bg-white p-4 shadow rounded">
        <h3 class="font-bold">Inventory Alerts</h3>

        @foreach($inventory_alerts['alerts'] ?? [] as $item)
            <div class="text-orange-600">
                {{ $item }}
            </div>
        @endforeach
    </div>

</div>

@endsection