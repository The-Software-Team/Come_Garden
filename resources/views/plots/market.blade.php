@extends('layouts.app')

@section('page-title', 'Available Garden Plots')

@section('content')
<script>
function toggleForm(id) {
    const form = document.getElementById('form-' + id);
    form.classList.toggle('active');
}
</script>

<div class="page-header">
    <h2>🌱 Choose Your Plot</h2>
    <p>Select a plot to rent based on size, soil, and location.</p>
</div>

<div class="plot-grid">

    @foreach($plots as $plot)

        <div class="plot-card">

            <div class="plot-header">
                <strong>Plot #{{ $plot->id }}</strong>
                <span class="badge badge-{{ $plot->status }}">
                    {{ $plot->status }}
                </span>
            </div>

            <div class="plot-body">
                <p>📏 Size: {{ $plot->size }}</p>
                <p>🌱 Soil: {{ $plot->soil_quality }}</p>
                <p>📐 Area: {{ $plot->area }}</p>
            </div>

<div class="plot-actions">
    <a href="{{ route('plots.show', $plot->id) }}" class="btn-secondary">
        View Details
    </a>

    <button onclick="toggleForm({{ $plot->id }})" class="btn-secondary">
        Apply
    </button>

    <form id="form-{{ $plot->id }}" method="POST"
          action="{{ route('rental.store') }}"
          class="hidden-form">

        @csrf
        <input type="hidden" name="plot_id" value="{{ $plot->id }}">

        <select name="share" class="input">
            <option value="1">Full</option>
            <option value="0.5">Half</option>
        </select>

        <button class="btn-primary">Confirm</button>
    </form>


</div>
        </div>

    @endforeach

</div>


@endsection