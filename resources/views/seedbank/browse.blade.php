@extends('layouts.app')
@section('title', 'Seed Bank — Browse')

@section('content')

@push('styles')
    @vite(['resources/css/domain/seedbank.css'])
@endpush

{{-- Alerts --}}
@if(session('message'))
    <div class="seedbank_alert seedbank_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif

@if(session('error') || $errors->any())
    <div class="seedbank_alert seedbank_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif


<div class="seedbank_browse">

    {{-- LEFT PANEL --}}
    <aside class="seedbank_browse_sidebar">

        <div class="seedbank_browse_sidebar_header">
            <p class="seedbank_section_label">Seed catalog</p>

            <div class="seedbank_search_wrap">
                <i class="ti ti-search"></i>
                <input id="seedbank_search"
                       class="seedbank_search"
                       type="search"
                       placeholder="Search seeds...">
            </div>
        </div>

        <ul class="seedbank_list" id="seedbank_list">

            @foreach($seeds as $seed)
                <li class="seedbank_list_item"
                    data-seed='@json($seed)'
                    tabindex="0">

                    <span class="seedbank_list_item_name">
                        {{ $seed['seed_type'] }}
                    </span>

                    <span class="seedbank_badge seedbank_badge--qty">
                        {{ $seed['quantity'] }}
                    </span>

                </li>
            @endforeach

        </ul>

    </aside>


    {{-- RIGHT PANEL --}}
    <div class="seedbank_browse_panel">

        {{-- EMPTY STATE --}}
        <div class="seedbank_panel_empty" id="empty">
            <h2>Select a seed</h2>
            <p>Choose a seed from the catalog.</p>
        </div>


        {{-- DETAIL --}}
        <div class="seedbank_panel_detail" id="detail" hidden>

            <div class="seedbank_detail_header">
                <h2 class="seedbank_detail_title" id="name"></h2>
                <span class="seedbank_badge seedbank_badge--qty" id="qty"></span>
            </div>

            <div class="seedbank_detail_stats">

                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Viability</span>
                    <span class="seedbank_stat_value">
                        <span id="viability"></span>%
                    </span>
                </div>

                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Age</span>
                    <span class="seedbank_stat_value" id="age"></span>
                </div>

                <div class="seedbank_stat">
                    <span class="seedbank_stat_label">Quantity</span>
                    <span class="seedbank_stat_value" id="qty2"></span>
                </div>

            </div>

            <div class="seedbank_detail_meta">
                <span class="seedbank_meta_item">
                    <i class="ti ti-map-pin"></i>
                    <span id="origin"></span>
                </span>
            </div>


            {{-- FORM --}}
            <div class="seedbank_withdraw_section">

                <p class="seedbank_section_label">Withdraw seeds</p>

                <form method="POST" action="{{ route('seedbank.withdraw') }}"
                      class="seedbank_withdraw_form">

                    @csrf

                    <input type="hidden" name="seed_type" id="type">

                    <input type="number"
                           name="quantity"
                           id="withdraw_qty"
                           class="seedbank_input"
                           min="1"
                           placeholder="Quantity">

                    <button class="seedbank_submit">
                        <i class="ti ti-plant"></i>
                        Withdraw
                    </button>

                </form>

            </div>

        </div>
    </div>

</div>

@endsection


@push('scripts')
<script>
let selectedSeed = null;

document.querySelectorAll('.seedbank_list_item').forEach(el => {

    el.addEventListener('click', () => {

        selectedSeed = JSON.parse(el.dataset.seed);

        document.getElementById('empty').style.display = 'none';
        document.getElementById('detail').hidden = false;

        document.getElementById('name').textContent = selectedSeed.seed_type;
        document.getElementById('qty').textContent = selectedSeed.quantity;
        document.getElementById('qty2').textContent = selectedSeed.quantity;
        document.getElementById('viability').textContent = selectedSeed.viability;
        document.getElementById('age').textContent = selectedSeed.age;
        document.getElementById('origin').textContent = selectedSeed.origin ?? '—';

        document.getElementById('type').value = selectedSeed.seed_type;
        // document.getElementById('ithdraw_qty').max = selectedSeed.quantity;
        document.getElementById('withdraw_qty').value = '';
    });

});


document.getElementById('seedbank_search').addEventListener('input', (e) => {
    const q = e.target.value.toLowerCase();

    document.querySelectorAll('.seedbank_list_item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});
</script>
@endpush