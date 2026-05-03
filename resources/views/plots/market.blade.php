@extends('layouts.app')

@section('page-title', 'Available Garden Plots')

@section('styles') 
@vite([
    'resources/css/domain/plots.css'
])
@endsection

@section('dynamics')
<script>
    const plots = @json($plots);
    let selectedPlot = null;

    function togglePlotMenu() {
        document.getElementById('plot-menu').classList.toggle('hidden');
    }

    function selectPlot(id) {
        selectedPlot = plots.find(p => p.id === id);

        // update button text
        document.getElementById('plot-select-btn').innerText = `Plot #${id} ▾`;

        renderPlot();
    }

    function renderPlot() {
        if (!selectedPlot) return;

        document.getElementById('plot-details').innerHTML = `
            <div class="details-header">
                <h2>Plot #${selectedPlot.id}</h2>
                <span class="badge">${selectedPlot.status}</span>
            </div>

            <div class="grid-info">

                <div class="info-box">
                    <div class="info-label">Size</div>
                    <div class="info-value">${selectedPlot.size}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Soil Quality</div>
                    <div class="info-value">${selectedPlot.soil_quality}</div>
                </div>

                <div class="info-box">
                    <div class="info-label">Area</div>
                    <div class="info-value">${selectedPlot.area}</div>
                </div>

            </div>

            <div class="section">
                <h3>🌱 Rent This Plot</h3>

                <form method="POST" action="{{ route('rental.store') }}">
                    @csrf
                    <input type="hidden" name="plot_id" value="${selectedPlot.id}">

                    <select name="share" class="input">
                        <option value="1">Full</option>
                        <option value="0.5">Half</option>
                    </select>

                    <button class="btn-primary mt-1">
                        Confirm Rental
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

<div class="page-header">
    <h2>🌱 Choose Your Plot</h2>
    <p>Select a plot to rent based on size, soil, and location.</p>
</div>

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: DROPDOWN SELECTOR --}}
    <div class="col-span-4">

        <button id="plot-select-btn" class="plot-select-btn" onclick="togglePlotMenu()">
            Select Plot ▾
        </button>

        <div id="plot-menu" class="plot-menu hidden">
            @foreach($plots as $plot)
                <div class="plot-menu-item"
                     onclick="selectPlot({{ $plot->id }}); togglePlotMenu()">

                    Plot #{{ $plot->id }}

                </div>
            @endforeach
        </div>

    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="plot-details">
                <div class="empty-state">
                    <h3>Select a Plot</h3>
                    <p>View details and rent your preferred plot.</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection