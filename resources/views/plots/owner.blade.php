@extends('layouts.app')

@section('page-title', "My Plot #{$plot->id}")

@section('content')

<div class="owner-grid">

    <!-- LEFT: CROPS -->
    <div class="panel">
        <h3>🌱 Crops</h3>

        @forelse($plot->crops as $crop)
            <div class="item">
                🌿 {{ $crop->type }}
                <small>by {{ $crop->user->name }}</small>
                <span class="tag">{{ $crop->stage }}</span>
            </div>
        @empty
            <p>No crops planted yet</p>
        @endforelse
    </div>

    <!-- CENTER: PLOT INFO -->
    <div class="panel main">
        <h3>📍 Plot Overview</h3>

        <p><strong>Status:</strong> {{ $plot->status }}</p>
        <p><strong>Soil:</strong> {{ $plot->soil_quality }}</p>
        <p><strong>Area:</strong> {{ $plot->area }}</p>

        <hr>

        <h4>🌦 Tasks</h4>
        <ul>
            <li>Watering schedule (coming soon)</li>
            <li>Winter tasks (coming soon)</li>
        </ul>
    </div>

    <!-- RIGHT: ALERTS -->
    <div class="panel">

        <h3>🚨 Alerts</h3>

        @forelse($plot->infections as $infection)
            <div class="alert">
                🦠 {{ $infection->type }} ({{ $infection->severity }})
            </div>
        @empty
            <p>No infections detected</p>
        @endforelse

        <hr>

        <h4>👥 Neighbors</h4>

        @foreach($plot->neighbors as $neighbor)
            <div class="neighbor">
                Plot #{{ $neighbor->id }}
            </div>
        @endforeach

    </div>

</div>

@endsection