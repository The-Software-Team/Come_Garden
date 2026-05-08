@extends('layouts.app')
 
@section('title', 'My Plot')
 
@push('styles')
    @vite(['resources/css/domain/plots.css'])
    <style>
        /* ── Owner page extras ── */
        :root {
            --soil-healthy:    #4a7c3f;
            --soil-healthy-bg: #e4eed9;
            --soil-neutral:    #6b7280;
            --soil-neutral-bg: #f3f4f6;
            --soil-depleted:   #c8860a;
            --soil-depleted-bg:#fdf3e0;
            --soil-recovering: #2668a8;
            --soil-recovering-bg: #e8f1fb;
        }
 
        /* ── Page hero ── */
        .pown_hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding: 0 0 18px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            margin-bottom: 20px;
        }
        .pown_hero h1 {
            font-family: var(--font-serif);
            font-size: 24px;
            font-weight: 600;
            margin: 0 0 4px;
        }
        .pown_hero p {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin: 0;
            display: flex; align-items: center; gap: 8px; flex-wrap: wrap;
        }
        .pown_hero_badges { display: flex; gap: 8px; flex-shrink: 0; align-items: center; }
 
        /* ── Soil state indicator ── */
        .pown_soil_state {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 4px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            border: 0.5px solid;
        }
        .pown_soil_state i { font-size: 13px; }
        .pown_soil_state--healthy    { background: var(--soil-healthy-bg);    color: var(--soil-healthy);    border-color: rgba(74,124,63,.25); }
        .pown_soil_state--neutral    { background: var(--soil-neutral-bg);    color: var(--soil-neutral);    border-color: rgba(107,114,128,.2); }
        .pown_soil_state--depleted   { background: var(--soil-depleted-bg);   color: var(--soil-depleted);   border-color: rgba(200,134,10,.25); }
        .pown_soil_state--recovering { background: var(--soil-recovering-bg); color: var(--soil-recovering); border-color: rgba(38,104,168,.25); }
 
        /* ── Soil health card ── */
        .pown_soil_card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
        }
        .pown_soil_header {
            padding: 14px 18px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex; align-items: center; justify-content: space-between;
        }
        .pown_soil_body { padding: 16px 18px; display: flex; flex-direction: column; gap: 14px; }
 
        /* Soil state ring */
        .pown_soil_visual {
            display: flex;
            align-items: center;
            gap: 16px;
        }
        .pown_soil_ring_wrap { flex-shrink: 0; }
        .pown_soil_ring_info { flex: 1; }
        .pown_soil_ring_title {
            font-size: 16px;
            font-weight: 600;
            color: var(--color-text-primary);
            margin: 0 0 4px;
            text-transform: capitalize;
        }
        .pown_soil_ring_desc {
            font-size: 12px;
            color: var(--color-text-secondary);
            margin: 0;
            line-height: 1.5;
        }
 
        /* Rule indicators */
        .pown_soil_rules { display: flex; flex-direction: column; gap: 6px; }
        .pown_soil_rule {
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 12px;
            color: var(--color-text-secondary);
            padding: 8px 10px;
            border-radius: var(--border-radius-md);
            background: var(--color-background-secondary);
            border: 0.5px solid var(--color-border-tertiary);
        }
        .pown_soil_rule i { font-size: 14px; flex-shrink: 0; }
        .pown_soil_rule--ok   i { color: var(--soil-healthy); }
        .pown_soil_rule--warn i { color: var(--soil-depleted); }
        .pown_soil_rule--info i { color: var(--soil-recovering); }

        /* ── Fertilizer option cards ── */
        .pown_fert_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(130px, 1fr));
            gap: 8px;
        }
        .pown_fert_option { position: relative; cursor: pointer; }
        .pown_fert_option input[type=radio] { position: absolute; opacity: 0; width: 0; height: 0; }
        .pown_fert_card {
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            padding: 11px 12px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            transition: border-color 0.15s, background 0.15s;
            background: var(--color-background-secondary);
        }
        .pown_fert_option input:checked + .pown_fert_card {
            border-color: var(--plot_moss);
            background: var(--plot_moss_bg);
        }
        .pown_fert_card_icon { font-size: 18px; line-height: 1; margin-bottom: 2px; }
        .pown_fert_card_name {
            font-size: 12px;
            font-weight: 600;
            color: var(--color-text-primary);
            line-height: 1.3;
        }
        .pown_fert_card_hint {
            font-size: 10px;
            color: var(--color-text-tertiary);
            line-height: 1.3;
        }
        /* Organic option gets a highlighted border to signal it's the soil-recovery trigger */
        .pown_fert_option--organic .pown_fert_card {
            border-color: rgba(74,124,63,.35);
        }
        .pown_fert_option--organic input:checked + .pown_fert_card {
            border-color: var(--soil-healthy);
            background: var(--soil-healthy-bg);
        }
        .pown_fert_organic_tag {
            display: inline-flex;
            align-items: center;
            gap: 3px;
            font-size: 9px;
            font-weight: 700;
            letter-spacing: 0.05em;
            text-transform: uppercase;
            color: var(--soil-healthy);
            background: var(--soil-healthy-bg);
            border: 0.5px solid rgba(74,124,63,.25);
            border-radius: 4px;
            padding: 1px 5px;
            margin-top: 2px;
            width: fit-content;
        }

        /* ── Stats bar (overview card) ── */
        .pown_stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-bottom: 0.5px solid var(--color-border-tertiary);
        }
        .pown_stat {
            padding: 14px 16px;
            border-right: 0.5px solid var(--color-border-tertiary);
        }
        .pown_stat:last-child { border-right: none; }
        .pown_stat_lbl {
            font-size: 10px; text-transform: uppercase; letter-spacing: .06em;
            color: var(--color-text-tertiary); display: block; margin-bottom: 5px;
        }
        .pown_stat_val {
            font-size: 18px; font-weight: 600;
            color: var(--color-text-primary);
            display: flex; align-items: baseline; gap: 2px; line-height: 1;
        }
        .pown_stat_val--sm { font-size: 13px; align-items: center; }
        .pown_stat_unit { font-size: 11px; color: var(--color-text-secondary); font-weight: 400; }
 
        /* ── Crops ── */
        .pown_crop_grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
            gap: 10px;
        }
        .pown_crop_card {
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            padding: 12px 14px;
            background: var(--color-background-secondary);
            display: flex; flex-direction: column; gap: 5px;
        }
        .pown_crop_icon { font-size: 18px; margin-bottom: 2px; }
        .pown_crop_name { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }
        .pown_crop_meta {
            font-size: 11px;
            color: var(--color-text-tertiary);
            display: flex; align-items: center; gap: 4px;
        }
        .pown_crop_meta i { font-size: 11px; }
 
        /* ── Infections ── */
        .pown_infection_item {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 12px;
            padding: 12px 14px;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            background: var(--color-background-secondary);
        }
        .pown_infection_name { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }
        .pown_infection_meta { font-size: 11px; color: var(--color-text-tertiary); margin-top: 3px; }
 
        /* ── Watering schedule ── */
        .pown_water_item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            background: var(--color-background-secondary);
        }
        .pown_water_icon {
            width: 34px; height: 34px;
            border-radius: 8px;
            background: var(--plot_sky_bg);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .pown_water_icon--thirsty { background: var(--plot_sun_bg); }
        .pown_water_icon i { font-size: 15px; color: var(--plot_sky); }
        .pown_water_icon--thirsty i { color: var(--plot_sun); }
        .pown_water_crop { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }
        .pown_water_time { font-size: 11px; color: var(--color-text-tertiary); margin-top: 3px; }
        .pown_water_amount {
            margin-left: auto; flex-shrink: 0;
            font-size: 11px; font-weight: 500;
            padding: 2px 8px;
            border-radius: 999px;
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-secondary);
            color: var(--color-text-secondary);
        }
 
        /* ── Neighbors ── */
        .pown_neighbor_item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
            padding: 10px 14px;
            border: 0.5px solid var(--plot_border);
            border-radius: var(--border-radius-md);
            background: var(--plot_moss_bg);
            font-size: 13px;
        }
        .pown_neighbor_left { display: flex; align-items: center; gap: 8px; }
        .pown_dir_badge {
            display: inline-flex; align-items: center; justify-content: center;
            width: 28px; height: 28px;
            border-radius: 6px;
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            font-size: 10px; font-weight: 700; color: var(--plot_moss);
            text-transform: uppercase; flex-shrink: 0;
        }
        .pown_neighbor_id { font-weight: 600; color: var(--color-text-primary); }
 
        /* ── Winter tasks ── */
        .pown_task_item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 10px 14px;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            font-size: 13px;
            color: var(--color-text-primary);
            background: var(--color-background-secondary);
            line-height: 1.5;
        }
        .pown_task_item i { color: var(--plot_moss); font-size: 15px; flex-shrink: 0; padding-top: 1px; }
 
        /* ── Form strip ── */
        .pown_form_strip {
            border-top: 0.5px solid var(--color-border-tertiary);
            padding-top: 14px;
            margin-top: 4px;
        }
        .pown_form_strip .plot_section_label { margin-bottom: 8px; }
        .pown_action_form { display: flex; gap: 10px; align-items: center; }
        .pown_action_form .plot_input { flex: 1; }
 
        @media (max-width: 860px) {
            .pown_stats { grid-template-columns: repeat(2, 1fr); }
            .plot_owner_layout { grid-template-columns: 1fr; }
        }
        @media (max-width: 540px) {
            .pown_stats { grid-template-columns: 1fr 1fr; }
            .pown_fert_grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
@endpush
 
@section('content')
 
@php
    $plotService = app(\App\Contracts\Plot\PlotServiceInterface::class);
    $wateringSchedule = $plotService->generateWateringSchedule($plot);
    $winterTasks      = $plotService->generateWinterTasks($plot);
 
    $zoneKey   = match($plot->sun_profile ?? 'center') { 'west' => 'west', 'east' => 'east', default => 'middle' };
    $zoneLabel = match($zoneKey) { 'west' => 'West Wing', 'east' => 'East Wing', default => 'Central' };
 
    $soilState = $plot->soil_quality ?? 'neutral';
    $soilMeta  = match($soilState) {
        'healthy'    => ['icon' => 'ti-heart',      'label' => 'Healthy',    'desc' => 'Good crop diversity is maintaining soil quality.'],
        'depleted'   => ['icon' => 'ti-alert-circle','label' => 'Depleted',  'desc' => 'Same crop repeated — consider rotation or organic treatment.'],
        'recovering' => ['icon' => 'ti-refresh',    'label' => 'Recovering', 'desc' => 'Organic fertiliser applied — soil is on the mend.'],
        default      => ['icon' => 'ti-minus',      'label' => 'Neutral',    'desc' => 'Plant more crops or add fertiliser to improve soil health.'],
    };
 
    $soilPct   = match($soilState) { 'healthy' => 85, 'recovering' => 60, 'neutral' => 40, 'depleted' => 20, default => 40 };
    $soilColor = match($soilState) { 'healthy' => '#4a7c3f', 'recovering' => '#2668a8', 'depleted' => '#c8860a', default => '#6b7280' };
 
    $recentCrops = $plot->activities()
        ->where('type', 'plant')
        ->latest()
        ->take(3)
        ->pluck('crop')
        ->values();
    $cropDiversity = $recentCrops->unique()->count();

    // Last fertilizer applied (for contextual hint in the form)
    $lastFertilizer = $plot->activities()
        ->where('type', 'fertilize')
        ->latest()
        ->first();
 
    $neighbors = $plot->neighbors()->withPivot('direction')->get();
    $dirLabels = ['north' => 'N', 'south' => 'S', 'east' => 'E', 'west' => 'W'];

    // Fertilizer options — value maps to what updateSoilState checks
    $fertOptions = [
        [
            'value'   => 'organic',
            'emoji'   => '🌿',
            'name'    => 'Organic Compost',
            'hint'    => 'Triggers soil recovery',
            'organic' => true,
        ],
        [
            'value'   => 'bone_meal',
            'emoji'   => '🦴',
            'name'    => 'Bone Meal',
            'hint'    => 'Boosts phosphorus',
            'organic' => false,
        ],
        [
            'value'   => 'npk_balanced',
            'emoji'   => '⚗️',
            'name'    => 'NPK Balanced',
            'hint'    => 'All-round nutrients',
            'organic' => false,
        ],
        [
            'value'   => 'seaweed',
            'emoji'   => '🌊',
            'name'    => 'Seaweed Extract',
            'hint'    => 'Trace minerals',
            'organic' => false,
        ],
        [
            'value'   => 'lime',
            'emoji'   => '🪨',
            'name'    => 'Garden Lime',
            'hint'    => 'Raises pH level',
            'organic' => false,
        ],
    ];
@endphp
 
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
 
{{-- Hero --}}
<div class="pown_hero">
    <div>
        <h1>Plot #{{ $plot->id }}</h1>
        <p>
            <i class="ti ti-map-pin" style="font-size:13px;"></i>
            {{ $zoneLabel }}
            &nbsp;·&nbsp;
            {{ $plot->width ?? '?' }}×{{ $plot->height ?? '?' }} m
            &nbsp;·&nbsp;
            {{ ucfirst($plot->size ?? '—') }} plot
        </p>
    </div>
    <div class="pown_hero_badges">
        <span class="pown_soil_state pown_soil_state--{{ $soilState }}">
            <i class="ti {{ $soilMeta['icon'] }}"></i>
            Soil: {{ $soilMeta['label'] }}
        </span>
        <span class="plot_badge plot_badge--{{ $plot->status }}">
            {{ ucfirst($plot->status) }}
        </span>
    </div>
</div>
 
<div class="plot_owner_layout">
 
    {{-- ══════════════════════════════
         LEFT — MAIN
    ══════════════════════════════ --}}
    <div class="plot_owner_main">
 
        {{-- OVERVIEW ── --}}
        <div class="plot_card">
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">My Allotment</p>
                    <h2 class="plot_card_title">Plot Overview</h2>
                </div>
            </div>
            <div class="pown_stats">
                <div class="pown_stat">
                    <span class="pown_stat_lbl">Area</span>
                    <span class="pown_stat_val">
                        {{ $plot->area ?? ($plot->width * $plot->height) ?? '—' }}<span class="pown_stat_unit">m²</span>
                    </span>
                </div>
                <div class="pown_stat">
                    <span class="pown_stat_lbl">Sun Profile</span>
                    <span class="pown_stat_val pown_stat_val--sm">
                        {{ match($plot->sun_profile ?? 'center') { 'east' => 'Morning Sun', 'west' => 'Afternoon Sun', default => 'Full Sun' } }}
                    </span>
                </div>
                <div class="pown_stat">
                    <span class="pown_stat_lbl">Crops</span>
                    <span class="pown_stat_val">{{ $plot->crops->count() }}</span>
                </div>
                <div class="pown_stat">
                    <span class="pown_stat_lbl">Neighbors</span>
                    <span class="pown_stat_val">{{ $neighbors->count() }}</span>
                </div>
            </div>
        </div>
 
 
        {{-- SOIL HEALTH ── --}}
        <div class="pown_soil_card">
            <div class="pown_soil_header">
                <div>
                    <p class="plot_section_label">Health Tracking</p>
                    <h3 class="plot_card_title">Soil State</h3>
                </div>
                <span class="pown_soil_state pown_soil_state--{{ $soilState }}">
                    <i class="ti {{ $soilMeta['icon'] }}"></i>
                    {{ $soilMeta['label'] }}
                </span>
            </div>
 
            <div class="pown_soil_body">

                {{-- Visual ring + description --}}
                <div class="pown_soil_visual">
                    <div class="pown_soil_ring_wrap">
                        @php
                            $r = 30; $cx = 38; $cy = 38;
                            $circ = 2 * M_PI * $r;
                            $dash = ($soilPct / 100) * $circ;
                        @endphp
                        <svg width="76" height="76" viewBox="0 0 76 76">
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                    fill="none" stroke="var(--color-border-tertiary)" stroke-width="5"/>
                            <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                    fill="none"
                                    stroke="{{ $soilColor }}"
                                    stroke-width="5"
                                    stroke-linecap="round"
                                    stroke-dasharray="{{ round($dash, 2) }} {{ round($circ, 2) }}"
                                    stroke-dashoffset="{{ round($circ / 4, 2) }}"
                                    transform="rotate(-90, {{ $cx }}, {{ $cy }})"/>
                            <text x="{{ $cx }}" y="{{ $cy + 5 }}"
                                  text-anchor="middle"
                                  font-size="13" font-weight="600"
                                  fill="{{ $soilColor }}"
                                  font-family="var(--font-sans)">{{ $soilPct }}%</text>
                        </svg>
                    </div>
                    <div class="pown_soil_ring_info">
                        <p class="pown_soil_ring_title">{{ $soilMeta['label'] }}</p>
                        <p class="pown_soil_ring_desc">{{ $soilMeta['desc'] }}</p>
                    </div>
                </div>
 
                {{-- Rule indicators --}}
                <div class="pown_soil_rules">
                    @if($recentCrops->count() >= 3 && $recentCrops->unique()->count() === 1)
                        <div class="pown_soil_rule pown_soil_rule--warn">
                            <i class="ti ti-alert-circle"></i>
                            Same crop planted 3 times in a row — soil is depleting.
                        </div>
                    @elseif($recentCrops->count() >= 2 && $cropDiversity > 1)
                        <div class="pown_soil_rule pown_soil_rule--ok">
                            <i class="ti ti-circle-check"></i>
                            Good crop diversity ({{ $cropDiversity }} types) — soil is staying healthy.
                        </div>
                    @endif
 
                    @if($soilState === 'recovering')
                        <div class="pown_soil_rule pown_soil_rule--info">
                            <i class="ti ti-refresh"></i>
                            Organic fertiliser detected — soil is in recovery mode.
                        </div>
                    @else
                        <div class="pown_soil_rule pown_soil_rule--ok">
                            <i class="ti ti-leaf"></i>
                            Tip: Apply organic compost to reset soil to "recovering" state.
                        </div>
                    @endif
                </div>


                {{-- ════════════════════════════════════
                     FERTILIZER FORM
                     ════════════════════════════════════ --}}
                <div class="pown_form_strip">

                    <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:10px;">
                        <p class="plot_section_label" style="margin:0;">Apply Fertilizer</p>
                        @if($lastFertilizer)
                            <span style="font-size:11px; color:var(--color-text-tertiary);">
                                <i class="ti ti-clock" style="font-size:11px; vertical-align:middle;"></i>
                                Last applied: {{ \Carbon\Carbon::parse($lastFertilizer->created_at)->diffForHumans() }}
                                · {{ str_replace('_', ' ', ucfirst($lastFertilizer->fertilizer ?? '—')) }}
                            </span>
                        @endif
                    </div>

                    <form method="POST"
                          action="{{ route('plots.fertilize', $plot) }}"
                          id="fert_form">
                        @csrf

                        {{-- Option cards --}}
                        <div class="pown_fert_grid" style="margin-bottom:14px;">
                            @foreach($fertOptions as $i => $opt)
                                <label class="pown_fert_option {{ $opt['organic'] ? 'pown_fert_option--organic' : '' }}">
                                    <input type="radio"
                                           name="fertilizer_type"
                                           value="{{ $opt['value'] }}"
                                           {{ $i === 0 ? 'checked' : '' }}
                                           required>
                                    <span class="pown_fert_card">
                                        <span class="pown_fert_card_icon">{{ $opt['emoji'] }}</span>
                                        <span class="pown_fert_card_name">{{ $opt['name'] }}</span>
                                        <span class="pown_fert_card_hint">{{ $opt['hint'] }}</span>
                                        @if($opt['organic'])
                                            <span class="pown_fert_organic_tag">
                                                <i class="ti ti-sparkles" style="font-size:9px;"></i>
                                                Recovers soil
                                            </span>
                                        @endif
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <button type="submit" class="plot_submit_btn" style="width:100%; justify-content:center;">
                            <i class="ti ti-droplet-half-2"></i>
                            Apply Fertilizer
                        </button>

                    </form>

                </div>

            </div>
        </div>
 
 
        {{-- CROPS ── --}}
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
                @if($plot->crops->isNotEmpty())
                    <div class="pown_crop_grid" style="margin-bottom:20px;">
                        @foreach($plot->crops as $crop)
                            @php
                                $cropEmoji = match(true) {
                                    str_contains(strtolower($crop->type), 'tomato')   => '🍅',
                                    str_contains(strtolower($crop->type), 'potato')   => '🥔',
                                    str_contains(strtolower($crop->type), 'carrot')   => '🥕',
                                    str_contains(strtolower($crop->type), 'lettuce')  => '🥬',
                                    str_contains(strtolower($crop->type), 'bean')     => '🫘',
                                    str_contains(strtolower($crop->type), 'onion')    => '🧅',
                                    str_contains(strtolower($crop->type), 'garlic')   => '🧄',
                                    str_contains(strtolower($crop->type), 'pepper')   => '🫑',
                                    str_contains(strtolower($crop->type), 'cucumber') => '🥒',
                                    str_contains(strtolower($crop->type), 'corn')     => '🌽',
                                    default                                            => '🌱',
                                };
                            @endphp
                            <div class="pown_crop_card">
                                <span class="pown_crop_icon">{{ $cropEmoji }}</span>
                                <span class="pown_crop_name">{{ $crop->type }}</span>
                                <span class="pown_crop_meta">
                                    <i class="ti ti-user"></i>
                                    {{ $crop->user->name ?? 'You' }}
                                </span>
                                <span class="pown_crop_meta">
                                    <i class="ti ti-calendar"></i>
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
 
                <div class="pown_form_strip">
                    <p class="plot_section_label">Plant a new crop</p>
                    <form method="POST" action="{{ route('plots.plant', $plot) }}" class="pown_action_form">
                        @csrf
                        <input type="text" name="type" class="plot_input"
                               placeholder="e.g. Tomatoes, Carrots, Beans" required>
                        <button type="submit" class="plot_submit_btn">
                            <i class="ti ti-plant"></i> Plant
                        </button>
                    </form>
                </div>
            </div>
        </div>
 
 
        {{-- INFECTIONS ── --}}
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
                    <span class="plot_badge plot_badge--available">
                        <i class="ti ti-circle-check" style="font-size:11px;"></i>
                        All clear
                    </span>
                @endif
            </div>
 
            <div class="plot_card_body">
                @if($plot->infections->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:8px;margin-bottom:20px;">
                        @foreach($plot->infections as $infection)
                            <div class="pown_infection_item">
                                <div>
                                    <p class="pown_infection_name">{{ $infection->type }}</p>
                                    <p class="pown_infection_meta">
                                        <i class="ti ti-clock" style="font-size:11px;"></i>
                                        Reported {{ \Carbon\Carbon::parse($infection->created_at)->diffForHumans() }}
                                        @if($infection->severity ?? false)
                                            &nbsp;·&nbsp; Severity: {{ ucfirst($infection->severity) }}
                                        @endif
                                    </p>
                                </div>
                                <span class="plot_badge plot_badge--{{ $infection->severity === 'warning' ? 'standard' : 'infected' }}">
                                    {{ $infection->severity === 'warning' ? 'Alert' : 'Active' }}
                                </span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_infection_empty" style="margin-bottom:20px;">
                        <i class="ti ti-mood-smile" style="font-size:16px;vertical-align:middle;margin-right:4px;"></i>
                        No active infections. Your plot is healthy!
                    </p>
                @endif
 
                <div class="pown_form_strip">
                    <p class="plot_section_label">Report an infection</p>
                    <form method="POST" action="{{ route('plots.reportInfection', $plot) }}" class="pown_action_form">
                        @csrf
                        <input type="text" name="type" class="plot_input"
                               placeholder="e.g. Potato Blight, Aphids, Mildew" required>
                        <button type="submit" class="plot_submit_btn"
                                style="background:var(--plot_sun);white-space:nowrap;">
                            <i class="ti ti-bug"></i> Report
                        </button>
                    </form>
                </div>
            </div>
        </div>
 
    </div>
 
 
    {{-- ══════════════════════════════
         RIGHT SIDEBAR
    ══════════════════════════════ --}}
    <div class="plot_owner_side">
 
        {{-- WATERING SCHEDULE ── --}}
        <div class="plot_card">
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Automation</p>
                    <h3 class="plot_card_title">Watering Schedule</h3>
                </div>
                <i class="ti ti-droplet" style="font-size:18px;color:var(--plot_sky);"></i>
            </div>
            <div class="plot_card_body">
                @if(!empty($wateringSchedule))
                    <div style="display:flex;flex-direction:column;gap:8px;">
                        @foreach($wateringSchedule as $entry)
                            <div class="pown_water_item">
                                <div class="pown_water_icon {{ ($entry['thirsty'] ?? false) ? 'pown_water_icon--thirsty' : '' }}">
                                    <i class="ti ti-droplet-filled"></i>
                                </div>
                                <div style="flex:1;min-width:0;">
                                    <p class="pown_water_crop">{{ $entry['crop'] }}</p>
                                    <p class="pown_water_time">{{ $entry['time'] }}</p>
                                </div>
                                <span class="pown_water_amount">{{ $entry['amount'] }}</span>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="plot_water_empty">No schedule available. Plant some crops first.</p>
                @endif
            </div>
        </div>
 
 
        {{-- NEIGHBORS ── --}}
        <div class="plot_card">
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Adjacent Plots</p>
                    <h3 class="plot_card_title">Neighbors</h3>
                </div>
                <span class="plot_badge plot_badge--standard">{{ $neighbors->count() }}</span>
            </div>
            <div class="plot_card_body">
                @if($neighbors->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($neighbors as $neighbor)
                            <div class="pown_neighbor_item">
                                <div class="pown_neighbor_left">
                                    <span class="pown_dir_badge">
                                        {{ $dirLabels[$neighbor->pivot->direction ?? 'north'] ?? '?' }}
                                    </span>
                                    <span class="pown_neighbor_id">Plot #{{ $neighbor->id }}</span>
                                </div>
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
 
 
        {{-- WINTER TASKS ── --}}
        <div class="plot_card">
            <div class="plot_card_header">
                <div>
                    <p class="plot_section_label">Seasonal</p>
                    <h3 class="plot_card_title">Winter Checklist</h3>
                </div>
                <i class="ti ti-snowflake" style="font-size:18px;color:var(--plot_sky);"></i>
            </div>
            <div class="plot_card_body">
                @if(!empty($winterTasks))
                    <div style="display:flex;flex-direction:column;gap:6px;">
                        @foreach($winterTasks as $task)
                            <div class="pown_task_item">
                                <i class="ti {{ $task['icon'] ?? 'ti-checkbox' }}"></i>
                                {{ $task['task'] ?? '' }}
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