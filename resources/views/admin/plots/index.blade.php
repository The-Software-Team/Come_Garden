@extends('layouts.app')

@section('title', 'Garden Control Center')

@section('styles')
@vite([
    'resources/css/domain/plots.css'
])
@endsection

@section('dynamics')

<script>
function togglePlotMenu() {
    document.getElementById('plot-menu').classList.toggle('hidden');
}

function showPlot(plot) {

    let applicationsHtml = '';

    if (plot.rental_applications && plot.rental_applications.length > 0 && plot.rental_applications.status == "pending") {
        plot.rental_applications.forEach(app => {
            applicationsHtml += `
                <div class="app-card">
                    <div>
                        <strong>Member #${app.member_id}</strong>
                        <span class="badge">${app.status}</span>
                    </div>

                    <div class="app-meta">
                        Share: ${app.share} |
                        Score: ${app.score}
                    </div>
                </div>
            `;
        });
    } else {
        applicationsHtml = `<p class="muted">No applications yet</p>`;
    }

    document.getElementById('plot-details').innerHTML = `

        <div class="details-header">
            <h2>Plot #${plot.id}</h2>
            <span class="badge">${plot.status}</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Size</div>
                <div class="info-value">${plot.size}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Soil Quality</div>
                <div class="info-value">${plot.soil_quality}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Area</div>
                <div class="info-value">${plot.area}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Infection</div>
                <div class="info-value">
                    ${plot.infection_status === 'infected' ? '🦠 Infected' : '🌱 Healthy'}
                </div>
            </div>

            <div class="info-box">
                <div class="info-label">Current Rental Share</div>
                <div class="info-value">
                    ${plot.current_rental_share ?? 0} / 1.0
                </div>
            </div>

        </div>

        <div class="section">

            <h3>📋 Applications</h3>

            <div class="applications">
                ${applicationsHtml}
            </div>

        </div>

        <div class="actions">

            <form method="POST" action="{{ route('rental.rent') }}">
                @csrf
                <input type="hidden" name="plot_id" value="${plot.id}">
                <button class="btn-primary">
                    🚀 Run Rental Allocation
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

    {{-- LEFT: SELECTOR --}}
    <div class="col-span-4">

        <h2>🌿 Plots</h2>

        <button class="plot-select-btn" onclick="togglePlotMenu()">
            Select Plot ▾
        </button>

        <div id="plot-menu" class="plot-menu hidden">
            @foreach($plots as $plot)
                <div class="plot-menu-item"
                     onclick='showPlot(@json($plot)); togglePlotMenu()'>

                    Plot #{{ $plot->id }}

                </div>
            @endforeach

        </div>

    <form method="POST" action="{{ route('rental.run') }}" class="mt-2">
        @csrf
        <button class="btn-primary">
            🌍 Run Allocation for All Plots
        </button>
    </form>


    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="plot-details">
                <div class="empty-state">
                    <h3>Select a Plot</h3>
                    <p>Choose a plot to manage applications and rental allocation.</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection