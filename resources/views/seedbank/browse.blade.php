@extends('layouts.app')

@section('title', 'Seed Bank')

@section('styles')
@vite([
'resources/css/domain/seedbank.css'
])
@endsection

@section('dynamics')
{{-- JS --}}
<script>
function showSeed(seed) {
    document.getElementById('seed-details').innerHTML = `

        <div class="details-header">
            <h2>${seed.seed_type}</h2>
            <span class="badge">${seed.quantity} available</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Viability</div>
                <div class="info-value">${seed.viability}%</div>
            </div>

            <div class="info-box">
                <div class="info-label">Average Age</div>
                <div class="info-value">${seed.age} years</div>
            </div>

            <div class="info-box">
                <div class="info-label">Origin</div>
                <div class="info-value">${seed.origin.join(', ')}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Quantity</div>
                <div class="info-value">${seed.quantity}</div>
            </div>


            <div class="info-box">
                <div class="info-label">Last Updated</div>
                <div class="info-value">${seed.updated_at}</div>
            </div>

        </div>

        <div class="withdraw-section">

            <form method="POST" action="{{ route('seedbank.withdraw') }}">
                @csrf

                <input type="hidden" name="seed_type" value="${seed.seed_type}">

                <input
                    type="number"
                    name="quantity"
                    min="1"
                    max="${seed.quantity}"
                    placeholder="Enter quantity to withdraw"
                    class="withdraw-input"
                >

                <button type="submit" class="withdraw-btn">
                    Withdraw Seeds
                </button>

            </form>

        </div>
    `;
}
</script>
@endsection
@if(session('message'))
    <div class="alert alert--success">
        {{ session('message') }}
    </div>
@endif

@section('content')
<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: Seed Names Only --}}
    <div class="col-span-4">
        <h2>🌱 Seed Catalog</h2>

        <div class="seed-list">
            @foreach($seeds as $seed)
                <div class="seed-list-item"
                     onclick='showSeed(@json($seed))'>

                    {{ $seed['seed_type'] }}
                </div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT: Details Panel --}}
    <div class="col-span-8">
        <div class="panel">

            <div id="seed-details">
                <div class="empty-state">
                    <h3>Select a Seed</h3>
                    <p>Choose a seed type from the left to view details and manage inventory.</p>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection