@extends('layouts.app')
 
@section('title', 'Plot & Rental Management')
 
@push('styles')
    @vite(['resources/css/domain/plots.css'])
@endpush
 
@section('content')
 
{{-- Alerts --}}
@if(session('message'))
    <div class="plot_alert plot_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif
 
@if(session('error') || $errors->any())
    <div class="plot_alert plot_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif
 
 
<div class="plot_admin_layout">
 
    {{-- ═══════════════════════════════════════
         STATS BAR
         ═══════════════════════════════════════ --}}
    <div class="plot_admin_stats">
 
        <div class="plot_admin_stat">
            <span class="plot_admin_stat_label">Total Plots</span>
            <span class="plot_admin_stat_value">{{ $plots->count() }}</span>
            <span class="plot_admin_stat_sub">Across all zones</span>
        </div>
 
        <div class="plot_admin_stat">
            <span class="plot_admin_stat_label">Rented</span>
            <span class="plot_admin_stat_value">
                {{ $plots->filter(fn($p) => $p->status === 'rented')->count() }}
            </span>
            <span class="plot_admin_stat_sub">Active leases</span>
        </div>
 
        <div class="plot_admin_stat">
            <span class="plot_admin_stat_label">Available</span>
            <span class="plot_admin_stat_value" style="color: var(--plot_moss);">
                {{ $plots->filter(fn($p) => $p->status === 'available')->count() }}
            </span>
            <span class="plot_admin_stat_sub">Open for applications</span>
        </div>
 
        <div class="plot_admin_stat">
            <span class="plot_admin_stat_label">Applications</span>
            <span class="plot_admin_stat_value" style="color: var(--plot_sun);">
                {{ $plots->sum(fn($p) => $p->rentalApplications->count()) }}
            </span>
            <span class="plot_admin_stat_sub">Pending review</span>
        </div>
 
    </div>
 
 
    {{-- ═══════════════════════════════════════
         BODY
         ═══════════════════════════════════════ --}}
    <div class="plot_admin_body">
 
 
        {{-- LEFT — PLOT TABLE ──────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Land Registry</p>
                    <h2 class="plot_card_title">All Plots</h2>
                </div>
 
                {{-- Manual rent a specific plot --}}
                <div style="display:flex; gap:10px; align-items:center;">
                    <form method="POST" action="{{ route('rental.rent') }}" style="display:flex; gap:8px; align-items:center;">
                        @csrf
                        <select name="plot_id" class="plot_input" style="width:140px; height:36px;">
                            @foreach($plots->where('status', 'available') as $plot)
                                <option value="{{ $plot->id }}">Plot #{{ $plot->id }}</option>
                            @endforeach
                        </select>
                        <button type="submit" class="plot_btn plot_btn--ghost">
                            <i class="ti ti-transfer"></i>
                            Assign
                        </button>
                    </form>
                </div>
            </div>
 
            <div class="plot_table_wrap">
 
                @if($plots->isNotEmpty())
 
                    <table class="plot_table">
                        <thead>
                            <tr>
                                <th>Plot</th>
                                <th>Soil Quality</th>
                                <th>Size</th>
                                <th>Status</th>
                                <th>Current Tenant</th>
                                <th>Infections</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
 
                            @foreach($plots as $plot)
                                <tr>
 
                                    <td>
                                        <p class="plot_table_name">Plot #{{ $plot->id }}</p>
                                        <p class="plot_table_sub">{{ $plot->zone ?? 'Zone A' }}</p>
                                    </td>
 
                                    <td>
                                        <span class="plot_badge plot_badge--{{ $plot->soil_quality === 'Premium Raised Beds' ? 'premium' : 'standard' }}">
                                            {{ $plot->soil_quality }}
                                        </span>
                                    </td>
 
                                    <td>{{ $plot->size }}m²</td>
 
                                    <td>
                                        <span class="plot_badge plot_badge--{{ $plot->status }}">
                                            {{ ucfirst($plot->status) }}
                                        </span>
                                    </td>
 
                                    <td>
                                        @if($plot->currentRental)
                                            @foreach($plot->currentRental->participants as $participant)
                                                <p class="plot_table_name" style="font-size:13px;">
                                                    {{ $participant->name }}
                                                </p>
                                            @endforeach
                                        @else
                                            <span style="font-size:12px; color:var(--color-text-tertiary);">—</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        @if($plot->infections->isNotEmpty())
                                            <span class="plot_badge plot_badge--infected">
                                                {{ $plot->infections->count() }} active
                                            </span>
                                        @else
                                            <span style="font-size:12px; color:var(--color-text-tertiary);">None</span>
                                        @endif
                                    </td>
 
                                    <td>
                                        <div class="plot_table_actions">
                                            <a href="{{ route('plots.show', $plot) }}"
                                               class="plot_btn plot_btn--ghost">
                                                <i class="ti ti-eye"></i>
                                                View
                                            </a>
                                        </div>
                                    </td>
 
                                </tr>
                            @endforeach
 
                        </tbody>
                    </table>
 
                @else
                    <div class="plot_table_empty">
                        <i class="ti ti-map-off" style="font-size:28px; display:block; margin-bottom:8px; color:var(--color-text-tertiary);"></i>
                        No plots registered yet.
                    </div>
                @endif
 
            </div>
 
        </div>
 
 
        {{-- RIGHT SIDEBAR ─────────────────── --}}
        <div style="display:flex; flex-direction:column; gap:16px;">
 
            {{-- RUN ALLOCATION ─────────────── --}}
            <div class="plot_allocation_card">
                <p class="plot_section_label">Seasonal Workflow</p>
                <h3 class="plot_allocation_title">Run Plot Allocation</h3>
                <p class="plot_allocation_desc">
                    Automatically assigns all pending rental applications to available plots
                    for the current active season, based on the priority queue.
                </p>
                <form method="POST" action="{{ route('rental.run') }}">
                    @csrf
                    <button type="submit" class="plot_submit_btn" style="width:100%; justify-content:center;">
                        <i class="ti ti-player-play"></i>
                        Run Allocation
                    </button>
                </form>
            </div>
 
 
            {{-- PENDING APPLICATIONS ─────── --}}
            <div class="plot_card">
 
                <div class="plot_card_header">
                    <div>
                        <p class="plot_section_label">Queue</p>
                        <h3 class="plot_card_title">Applications</h3>
                    </div>
                    <span class="plot_badge plot_badge--premium">
                        {{ $plots->sum(fn($p) => $p->rentalApplications->count()) }} pending
                    </span>
                </div>
 
                <div class="plot_card_body">
 
                    @php
                        $allApplications = $plots->flatMap(fn($p) => $p->rentalApplications->map(fn($a) => ['plot' => $p, 'application' => $a]));
                    @endphp
 
                    @if($allApplications->isNotEmpty())
 
                        <div class="plot_app_list">
                            @foreach($allApplications->take(8) as $item)
                                @php $app = $item['application']; $plot = $item['plot']; @endphp
                                <div class="plot_app_card">
 
                                    <div class="plot_app_header">
                                        <div>
                                            <p class="plot_app_name">{{ $app->member->name ?? 'Unknown' }}</p>
                                            <p class="plot_app_meta">
                                                Plot #{{ $plot->id }} ·
                                                {{ \Carbon\Carbon::parse($app->created_at)->format('d M Y') }}
                                            </p>
                                        </div>
                                        <span class="plot_badge plot_badge--{{ $app->status ?? 'standard' }}">
                                            {{ ucfirst($app->status ?? 'pending') }}
                                        </span>
                                    </div>
 
                                    @if($app->message ?? false)
                                        <p style="font-size:12px; color:var(--color-text-secondary); margin:0 0 10px; line-height:1.5;">
                                            {{ Str::limit($app->message, 80) }}
                                        </p>
                                    @endif
 
                                </div>
                            @endforeach
                        </div>
 
                        @if($allApplications->count() > 8)
                            <p style="font-size:12px; color:var(--color-text-tertiary); text-align:center; margin-top:12px;">
                                +{{ $allApplications->count() - 8 }} more applications
                            </p>
                        @endif
 
                    @else
                        <p class="plot_infection_empty">No pending applications.</p>
                    @endif
 
                </div>
 
            </div>
 
        </div>
 
    </div>
 
</div>
 
@endsection
