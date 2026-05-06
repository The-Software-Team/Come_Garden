@extends('layouts.app')
@section('page-title', 'Seed Bank — My Profile')

@push('styles')
    @vite(['resources/css/domain/seedbank.css'])
@endpush

@section('content')

@if(session('message'))
<div class="seedbank_alert seedbank_alert--success" role="alert">
    <i class="ti ti-circle-check" aria-hidden="true"></i>
    <span>{{ session('message') }}</span>
</div>
@endif

{{-- Top bar: member info + credits --}}
<div class="seedbank_profile_top">

    <div class="seedbank_card seedbank_member_card">
        <!-- <div class="seedbank_avatar">
            <i class="ti ti-user" aria-hidden="true"></i>
        </div> -->
        <div>
            <p class="seedbank_member_name">{{ auth()->user()->name }}</p>
            <p class="seedbank_member_sub">
                Member since {{ auth()->user()->created_at->format('M Y') }} &middot; Seed Bank
            </p>
        </div>
    </div>

    <div class="seedbank_credits_card">
        <p class="seedbank_credits_label">Seed credits</p>
        <p class="seedbank_credits_value">{{ number_format($credits) }}</p>
        <p class="seedbank_credits_sub">Earned from market deposits</p>
    </div>

</div>

{{-- Seeds grid --}}
<p class="seedbank_section_label">
    Your seeds
    @if($seeds->count())
        <span class="seedbank_count_pill">{{ $seeds->count() }}</span>
    @endif
</p>

@forelse($seeds as $seed)

    @if($loop->first)
    <div class="seedbank_seed_grid">
    @endif

    <div class="seedbank_seed_card">
        <div class="seedbank_seed_card_top">
            <p class="seedbank_seed_name">{{ $seed->seed_type }}</p>
            <span class="seedbank_badge seedbank_badge--{{ $seed->owner_type }}">
                {{ ucfirst($seed->owner_type) }}
            </span>
        </div>
        <div class="seedbank_seed_stats">
            <div class="seedbank_seed_stat">
                <span class="seedbank_seed_stat_label">Qty</span>
                <span class="seedbank_seed_stat_value">{{ $seed->quantity }}</span>
            </div>
            <div class="seedbank_seed_stat">
                <span class="seedbank_seed_stat_label">Viability</span>
                <span class="seedbank_seed_stat_value">{{ $seed->viability }}%</span>
            </div>
            <div class="seedbank_seed_stat">
                <span class="seedbank_seed_stat_label">Age</span>
                <span class="seedbank_seed_stat_value">{{ $seed->age }} yr</span>
            </div>
            <div class="seedbank_seed_stat">
                <span class="seedbank_seed_stat_label">Origin</span>
                <span class="seedbank_seed_stat_value">{{ $seed->origin ?? '—' }}</span>
            </div>
        </div>
        <!-- <div class="seedbank_seed_footer">
            <span class="seedbank_seed_date">
                <i class="ti ti-calendar" aria-hidden="true"></i>
                Added {{ \Carbon\Carbon::parse($seed->created_at)->format('M d, Y') }}
            </span>
        </div> -->
    </div>

    @if($loop->last)
    </div>{{-- .seedbank_seed_grid --}}
    @endif

@empty
    <div class="seedbank_empty_state">
        <p>You have no seeds yet. Deposit your first batch to get started.</p>
        <a href="{{ route('seedbank.deposit') }}" class="seedbank_submit">
            <i class="ti ti-leaf" aria-hidden="true"></i>
            Deposit seeds
        </a>
    </div>
@endforelse

@endsection