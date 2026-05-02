@extends('layouts.app')

@section('page-title', 'Garden Control Center')

@section('content')
<div class="admin-header">
    <h2>🏛 Garden Overview</h2>
    <p>System-wide plot layout and health status</p>
</div>

<div class="admin-grid">

    @foreach($plots as $plot)

        <div class="admin-plot status-{{ $plot->status }}">

            <div class="admin-title">
                Plot #{{ $plot->id }}
            </div>

            <div class="admin-info">
                <p>Size: {{ $plot->size }}</p>
                <p>Soil: {{ $plot->soil_quality }}</p>
                <p>Area: {{ $plot->area }}</p>
            </div>

            <div class="admin-status">
                @if($plot->infection_status === 'infected')
                    <span class="danger">🦠 Infected</span>
                @else
                    <span class="ok">🌱 Healthy</span>
                @endif
            </div>

        </div>

    @endforeach
</div>

@endsection