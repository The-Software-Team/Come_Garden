@extends('layouts.app')

@section('page-title', "Plot #{$plot->id}")

@section('content')

<div class="plot-detail">

    <!-- LEFT: CROPS -->
    <div class="panel">
        <h2>🌱 Crops</h2>

        @forelse($plot->crops as $crop)
            <div>
                {{ $crop->type }}
                ({{ $crop->user->name ?? 'Unknown' }})
            </div>
        @empty
            <p>No crops planted</p>
        @endforelse
    </div>

    <!-- CENTER -->
    <div class="panel main">
        <h2>Plot Info</h2>

        <p>Status: {{ $plot->status }}</p>
        <p>Soil: {{ $plot->soil_quality }}</p>
    </div>

    <!-- RIGHT: ALERTS -->
    <div class="panel">
        <h2>🚨 Alerts</h2>

        @forelse($plot->alerts as $alert)
            <div class="alert">
                {{ $alert['message'] }}
            </div>
        @empty
            <p>No alerts</p>
        @endforelse
    </div>

</div>

@endsection