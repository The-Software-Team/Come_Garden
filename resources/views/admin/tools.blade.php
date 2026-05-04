@extends('layouts.app')

@section('title', 'Admin - Tool Management')

@section('styles')
@vite([
'resources/css/domain/tools.css'
])
@endsection
@section('dynamics')
<script>

function showTool(tool) {

    document.getElementById('tool-details').innerHTML = `

        <div class="details-header">
            <h2>${tool.name}</h2>
            <span class="badge">${tool.status}</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Usage Status</div>
                <div class="info-value">${tool.usage_status}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Total Bookings</div>
                <div class="info-value">${tool.bookings_count}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Maintenance Threshold</div>
                <div class="info-value">${tool.maintenance_threshold_hours}</div>
            </div>

        </div>

    `;
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

    {{-- LEFT: TOOL LIST --}}
    <div class="col-span-4">

        <h2>🔧 Tools</h2>

        {{-- ADD TOOL FORM --}}
        <div class="panel">

            <h3>Add New Tool</h3>

            <form method="POST" action="{{ route('admin.tools.store') }}">
                @csrf

                <input type="text" name="name" placeholder="Tool name" class="input" required>

                <input type="number" name="maintenance_threshold_hours"
                       placeholder="Maintenance threshold"
                       class="input">

                <select name="usage_status" class="input">
                    <option value="low">Low</option>
                    <option value="medium">Medium</option>
                    <option value="high">High</option>
                </select>

                <button type="submit" class="btn-primary">
                    Create Tool
                </button>

            </form>

        </div>

        {{-- TOOL LIST --}}
        <div class="tool-list mt-4">

            @foreach($tools as $tool)
                <div class="tool-item"
                     onclick='showTool(@json($tool))'>

                    <div>
                        {{ $tool->name }}
                    </div>

                    <span class="status">{{ $tool->status }}</span>

                </div>
            @endforeach

        </div>

    </div>

    {{-- RIGHT: DETAILS PANEL --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="tool-details">
                <div class="empty-state">
                    <h3>Admin Tool Dashboard</h3>
                    <p>Select a tool to view analytics and details.</p>
                </div>
            </div>

        </div>

        {{-- FUTURE SECTION (placeholder for your roadmap) --}}
        <div class="panel mt-6">

            <h3>📊 System Overview</h3>

            <div class="grid-info">

                <div class="info-box">
                    <div class="info-label">Total Tools</div>
                    <div class="info-value">{{ $tools->count() }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Available</div>
                    <div class="info-value">{{ $tools->where('status', 'available')->count() }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">In Use</div>
                    <div class="info-value">{{ $tools->where('status', 'in_use')->count() }}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Maintenance</div>
                    <div class="info-value">{{ $tools->where('status', 'maintenance')->count() }}</div>
                </div>

            </div>

        </div>

    </div>

</div>
@endsection