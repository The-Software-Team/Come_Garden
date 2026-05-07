@extends('layouts.app')
 
@section('title', 'My Plot')
 
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
 
 
<div class="plot_owner_layout">
 
    {{-- ═══════════════════════════════════════
         LEFT — MAIN CONTENT
         ═══════════════════════════════════════ --}}
    <div class="plot_owner_main">
 
        {{-- PLOT OVERVIEW ─────────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">My Allotment</p>
                    <h2 class="plot_card_title">Plot #{{ $plot->id }}</h2>
                </div>
                <span class="plot_badge plot_badge--{{ $plot->status }}">
                    {{ ucfirst($plot->status) }}
                </span>
            </div>
 
            <div class="plot_detail_stats" style="border-bottom:none;">
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Size</span>
                    <span class="plot_stat_value">
                        {{ $plot->size }}<span class="plot_stat_unit">m²</span>
                    </span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Sunlight</span>
                    <span class="plot_stat_value plot_stat_value--sm">
                        {{ $plot->sunlight_exposure ?? '—' }}
                    </span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Soil Quality</span>
                    <span class="plot_stat_value plot_stat_value--sm">
                        {{ $plot->soil_quality ?? '—' }}
                    </span>
                </div>
 
                <div class="plot_stat">
                    <span class="plot_stat_label">Monthly Fee</span>
                    <span class="plot_stat_value">
                        £{{ $plot->monthly_fee ?? '—' }}
                    </span>
                </div>
 
            </div>
 
        </div>
 
 
        {{-- CROPS ────────────────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Planted</p>
                    <h3 class="plot_card_title">My Crops</h3>
                </div>
                <span class="plot_badge plot_badge--standard">
                    {{ $plot->crops->count() }} crops
                </span>
            </div>
 
            <div class="plot_card_body">
 
                {{-- Crop grid --}}
                @if($plot->crops->isNotEmpty())
                    <div class="plot_crop_grid" style="margin-bottom:20px;">
                        @foreach($plot->crops as $crop)
                            <div class="plot_crop_card">
                                <span class="plot_crop_name">{{ $crop->type }}</span>
                                <span class="plot_crop_meta">
                                    <i class="ti ti-user" style="font-size:11px;"></i>
                                    {{ $crop->user->name ?? 'You' }}
                                </span>
                                <span class="plot_crop_meta">
                                    <i class="ti ti-calendar" style="font-size:11px;"></i>
                                    {{ \Carbon\Carbon::parse($crop->created_at)->format('d M Y') }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_crop_empty" style="margin-bottom:20px;">
                        No crops planted yet. Use the form below to add your first crop.
                    </p>
                @endif
 
                {{-- Plant crop form --}}
                <div style="border-top: 0.5px solid var(--color-border-tertiary); padding-top: 16px;">
                    <p class="plot_section_label">Plant a new crop</p>
                    <form method="POST"
                          action="{{ route('plots.plant', $plot) }}"
                          class="plot_action_form">
                        @csrf
                        <input type="text"
                               name="type"
                               class="plot_input"
                               placeholder="Crop type (e.g. Tomatoes)"
                               required>
                        <button type="submit" class="plot_submit_btn">
                            <i class="ti ti-plant"></i>
                            Plant
                        </button>
                    </form>
                </div>
 
            </div>
 
        </div>
 
 
        {{-- INFECTIONS ───────────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Health</p>
                    <h3 class="plot_card_title">Pest & Disease Log</h3>
                </div>
                @if($plot->infections->isNotEmpty())
                    <span class="plot_badge plot_badge--infected">
                        <i class="ti ti-alert-triangle" style="font-size:11px;"></i>
                        {{ $plot->infections->count() }} active
                    </span>
                @else
                    <span class="plot_badge plot_badge--available">All clear</span>
                @endif
            </div>
 
            <div class="plot_card_body">
 
                @if($plot->infections->isNotEmpty())
                    <div class="plot_infection_list" style="margin-bottom:20px;">
                        @foreach($plot->infections as $infection)
                            <div class="plot_infection_item">
                                <div>
                                    <p class="plot_infection_name">{{ $infection->type }}</p>
                                    <p class="plot_infection_date">
                                        Reported {{ \Carbon\Carbon::parse($infection->created_at)->diffForHumans() }}
                                    </p>
                                </div>
                                <span class="plot_badge plot_badge--infected">Active</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_infection_empty" style="margin-bottom:20px;">
                        No active infections. Your plot is healthy!
                    </p>
                @endif
 
                {{-- Report infection form --}}
                <div style="border-top: 0.5px solid var(--color-border-tertiary); padding-top: 16px;">
                    <p class="plot_section_label">Report an infection</p>
                    <form method="POST"
                          action="{{ route('plots.reportInfection', $plot) }}"
                          class="plot_action_form">
                        @csrf
                        <input type="text"
                               name="type"
                               class="plot_input"
                               placeholder="e.g. Potato Blight, Aphids"
                               required>
                        <button type="submit" class="plot_submit_btn" style="background: var(--plot_sun); white-space:nowrap;">
                            <i class="ti ti-bug"></i>
                            Report
                        </button>
                    </form>
                </div>
 
            </div>
 
        </div>
 
    </div>
 
 
    {{-- ═══════════════════════════════════════
         RIGHT SIDEBAR
         ═══════════════════════════════════════ --}}
    <div class="plot_owner_side">
 
        {{-- WATERING SCHEDULE ────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Automation</p>
                    <h3 class="plot_card_title">Watering Schedule</h3>
                </div>
                <i class="ti ti-droplet" style="font-size:18px; color: var(--plot_sky);"></i>
            </div>
 
            <div class="plot_card_body">
 
                @php
                    $schedule = app(\App\Contracts\Plot\PlotServiceInterface::class)
                        ->generateWateringSchedule($plot);
                @endphp
 
                @if(!empty($schedule))
                    <div class="plot_water_list">
                        @foreach($schedule as $entry)
                            <div class="plot_water_item">
                                <div class="plot_water_icon">
                                    <i class="ti ti-droplet-filled"></i>
                                </div>
                                <div>
                                    <p class="plot_water_crop">{{ $entry['crop'] ?? $entry['type'] ?? 'Crop' }}</p>
                                    <p class="plot_water_time">{{ $entry['time'] ?? $entry['schedule'] ?? '—' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_water_empty">
                        No schedule available. Plant some crops first.
                    </p>
                @endif
 
            </div>
 
        </div>
 
 
        {{-- NEIGHBORS ────────────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Adjacent Plots</p>
                    <h3 class="plot_card_title">Neighbors</h3>
                </div>
                <span class="plot_badge plot_badge--standard">
                    {{ $plot->neighbors->count() }}
                </span>
            </div>
 
            <div class="plot_card_body">
 
                @if($plot->neighbors->isNotEmpty())
                    <div class="plot_neighbor_list">
                        @foreach($plot->neighbors as $neighbor)
                            <div class="plot_neighbor_item">
                                <span>Plot #{{ $neighbor->id }}</span>
                                @if($neighbor->infections->isNotEmpty())
                                    <span class="plot_badge plot_badge--infected">
                                        <i class="ti ti-alert-triangle" style="font-size:10px;"></i>
                                        Infected
                                    </span>
                                @else
                                    <span class="plot_badge plot_badge--available">Clear</span>
                                @endif
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_infection_empty">No adjacent plots found.</p>
                @endif
 
            </div>
 
        </div>
 
 
        {{-- WINTER TASKS ─────────────────── --}}
        <div class="plot_card">
 
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Seasonal</p>
                    <h3 class="plot_card_title">Winter Checklist</h3>
                </div>
                <i class="ti ti-snowflake" style="font-size:18px; color: var(--plot_sky);"></i>
            </div>
 
            <div class="plot_card_body">
 
                @php
                    $winterTasks = app(\App\Contracts\Plot\PlotServiceInterface::class)
                        ->generateWinterTasks($plot);
                @endphp
 
                @if(!empty($winterTasks))
                    <div class="plot_task_list">
                        @foreach($winterTasks as $task)
                            <div class="plot_task_item">
                                <i class="ti ti-checkbox"></i>
                                {{ is_string($task) ? $task : ($task['description'] ?? $task['task'] ?? '') }}
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_water_empty">No winter tasks generated yet.</p>
                @endif
 
            </div>
 
        </div>
 
    </div>
 
</div>
 
@endsection
 