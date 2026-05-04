@extends('layouts.app')

@section('title', 'Tool Library')

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
                <div class="info-label">Total Hours</div>
                <div class="info-value">${tool.total_usage_hours}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Maintenance Threshold</div>
                <div class="info-value">${tool.maintenance_threshold_hours}</div>
            </div>

        </div>

        <div class="action-section">

            <form method="POST" action="{{ route('tools.book') }}">
                @csrf

                <input type="hidden" name="tool_name" value="${tool.name}">

                <input type="number"
                    name="duration_hours"
                    min="1"
                    placeholder="Hours"
                    class="input">

                <button type="submit" class="btn-primary">
                    Book Tool
                </button>

            </form>

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

        <div class="tool-list">
            @foreach($tools as $tool)
                <div class="tool-item"
                     onclick='showTool(@json($tool))'>

                    {{ $tool->name }}

                    <span class="status">{{ $tool->status }}</span>
                </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="tool-details">
                <div class="empty-state">
                    <h3>Select a Tool</h3>
                    <p>View details, book tools, and manage your usage.</p>
                </div>
            </div>

        </div>

        {{-- BOOKINGS SECTION --}}
        <div class="panel mt-6">

            <h3>📋 My Active Bookings</h3>

            @foreach($bookings as $booking)
                <div class="booking-card">

                    <div>
                        <strong>Tool:</strong> {{ $booking->tool->name }}
                    </div>

                    <div>
                        <strong>Due:</strong> {{ $booking->end_time }}
                    </div>

                    <div>
                        <strong>Status:</strong> {{ $booking->status }}
                    </div>

                    <div class="actions">

                        {{-- RETURN --}}
                        <form method="POST" action="{{ route('tools.return') }}">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                            <button class="btn-success">Return</button>
                        </form>

                        {{-- DAMAGE --}}
                        <form method="POST" action="{{ route('tools.damage') }}">
                            @csrf
                            <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                            <select name="severity">
                                <option value="low">Low</option>
                                <option value="medium">Medium</option>
                                <option value="high">High</option>
                            </select>

                            <button class="btn-danger">Report</button>
                        </form>

                    </div>

                </div>
            @endforeach

        </div>

    </div>

</div>
@endsection