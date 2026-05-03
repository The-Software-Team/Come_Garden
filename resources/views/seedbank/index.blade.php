@extends('layouts.app')

@section('page-title', '🌾 Seed Bank')

@section('content')

<div class="grid">

    <!-- Seeds Panel -->
    <div class="panel">
        <h3>🌱 Your Seeds</h3>

        @forelse($seeds as $seed)
            <div class="seed-card">
                <div class="seed-header">
                    <strong>{{ $seed['seed_type'] }}</strong>
                    <span class="badge">{{ ucfirst($seed['owner_type']) }}</span>
                </div>

                <div class="seed-body">
                    <p><strong>Quantity:</strong> {{ $seed['quantity'] }}</p>
                    <p><strong>Viability:</strong> {{ $seed['viability'] }}%</p>
                    <p><strong>Age:</strong> {{ $seed['age'] }} years</p>
                    <p><strong>Origin:</strong> {{ $seed['origin'] }}</p>
                </div>

                <div class="seed-footer">
                    <small>Added: {{ \Carbon\Carbon::parse($seed['created_at'])->format('M d, Y') }}</small>
                </div>
            </div>
        @empty
            <p>No seeds available 🌾</p>
        @endforelse
    </div>

    <!-- Credits Panel -->
    <div class="panel">
        <h3>💰 Your Credits</h3>
        <p class="credits">{{ $credits }}</p>
    </div>

</div>

@endsection