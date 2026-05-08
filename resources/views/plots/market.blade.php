@extends('layouts.app')

@section('title', 'Plot Market')

@push('styles')
    @vite(['resources/css/domain/plots.css'])
    <style>
        /* ── Market page overrides & enhancements ── */

        /* Zone color tokens */
        :root {
            --zone-west:   #2668a8;
            --zone-west-bg: #e8f1fb;
            --zone-mid:    #4a7c3f;
            --zone-mid-bg: #e4eed9;
            --zone-east:   #c8860a;
            --zone-east-bg: #fdf3e0;
        }

        /* ── Hero strip ── */
        .pmkt_hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 0 18px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            margin-bottom: 20px;
            gap: 16px;
        }
        .pmkt_hero_left h1 {
            font-family: var(--font-serif);
            font-size: 26px;
            font-weight: 600;
            margin: 0 0 4px;
            color: var(--color-text-primary);
        }
        .pmkt_hero_left p {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin: 0;
        }
        .pmkt_hero_pills {
            display: flex;
            gap: 10px;
            flex-shrink: 0;
        }
        .pmkt_pill {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 12px;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 500;
            border: 0.5px solid;
        }
        .pmkt_pill--green  { background: var(--plot_moss_bg); color: var(--plot_moss); border-color: var(--plot_border); }
        .pmkt_pill--amber  { background: var(--plot_sun_bg);  color: var(--plot_sun_text); border-color: rgba(200,134,10,.22); }
        .pmkt_pill i { font-size: 13px; }

        /* ── Layout ── */
        .pmkt_layout {
            display: grid;
            grid-template-columns: 280px minmax(0, 1fr);
            gap: 16px;
            min-height: 620px;
        }

        /* ── Sidebar ── */
        .pmkt_sidebar {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }
        .pmkt_sidebar_head {
            padding: 14px 16px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex;
            flex-direction: column;
            gap: 10px;
        }
        .pmkt_sidebar_title {
            font-size: 11px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--color-text-tertiary);
            margin: 0;
        }

        /* Search */
        .pmkt_search_wrap   { position: relative; }
        .pmkt_search_wrap i { position: absolute; left: 9px; top: 50%; transform: translateY(-50%); font-size: 13px; color: var(--color-text-tertiary); pointer-events: none; }
        .pmkt_search {
            width: 100%; height: 34px;
            padding: 0 10px 0 30px;
            border: 0.5px solid var(--color-border-secondary);
            border-radius: var(--border-radius-md);
            background: var(--color-background-secondary);
            font-size: 13px; color: var(--color-text-primary);
            box-sizing: border-box; outline: none;
            transition: border-color .15s, box-shadow .15s;
        }
        .pmkt_search:focus { border-color: var(--plot_moss); box-shadow: 0 0 0 3px var(--plot_focus); }

        /* Zone filter tabs */
        .pmkt_tabs {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 4px;
        }
        .pmkt_tab {
            height: 28px;
            border: 0.5px solid var(--color-border-secondary);
            border-radius: var(--border-radius-md);
            background: transparent;
            font-size: 11px;
            font-weight: 500;
            color: var(--color-text-secondary);
            cursor: pointer;
            transition: all .12s;
            display: flex; align-items: center; justify-content: center;
        }
        .pmkt_tab:hover { background: var(--plot_moss_light); border-color: var(--plot_moss); color: var(--plot_moss); }
        .pmkt_tab.active { background: var(--plot_moss); border-color: var(--plot_moss); color: #fff; }
        .pmkt_tab[data-zone="west"].active   { background: var(--zone-west); border-color: var(--zone-west); }
        .pmkt_tab[data-zone="middle"].active { background: var(--zone-mid);  border-color: var(--zone-mid);  }
        .pmkt_tab[data-zone="east"].active   { background: var(--zone-east); border-color: var(--zone-east); }

        /* Plot list */
        .pmkt_list { list-style: none; margin: 0; padding: 0; overflow-y: auto; flex: 1; }

        .pmkt_item {
            padding: 12px 16px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            cursor: pointer;
            transition: background .1s;
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        .pmkt_item:last-child { border-bottom: none; }
        .pmkt_item:hover { background: var(--color-background-secondary); }
        .pmkt_item.active {
            background: var(--plot_moss_bg);
            border-left: 2px solid var(--plot_moss);
            padding-left: 14px;
        }

        .pmkt_item_top {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .pmkt_item_name { font-size: 13px; font-weight: 600; color: var(--color-text-primary); }

        .pmkt_item_bottom {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 6px;
        }
        .pmkt_item_dims {
            font-size: 11px;
            color: var(--color-text-tertiary);
            display: flex; align-items: center; gap: 4px;
        }
        .pmkt_item_dims i { font-size: 11px; }

        /* Zone dot */
        .pmkt_zone_dot {
            width: 7px; height: 7px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .pmkt_zone_dot--west   { background: var(--zone-west); }
        .pmkt_zone_dot--middle { background: var(--zone-mid); }
        .pmkt_zone_dot--east   { background: var(--zone-east); }

        /* Size badge */
        .pmkt_size_chip {
            font-size: 10px;
            font-weight: 500;
            padding: 2px 7px;
            border-radius: 999px;
            border: 0.5px solid var(--color-border-secondary);
            color: var(--color-text-secondary);
            background: var(--color-background-secondary);
            text-transform: capitalize;
        }

        /* Empty list */
        .pmkt_empty {
            padding: 2rem;
            text-align: center;
            display: flex; flex-direction: column; align-items: center; gap: 8px;
            color: var(--color-text-secondary); font-size: 13px;
        }
        .pmkt_empty i { font-size: 26px; color: var(--color-text-tertiary); }

        /* ── Right panel ── */
        .pmkt_panel {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        /* Empty state */
        .pmkt_panel_blank {
            flex: 1;
            display: flex; flex-direction: column; align-items: center; justify-content: center;
            gap: 12px; padding: 3rem; text-align: center;
        }
        .pmkt_panel_blank_icon {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: var(--plot_moss_light);
            display: flex; align-items: center; justify-content: center;
        }
        .pmkt_panel_blank_icon i { font-size: 26px; color: var(--plot_moss); }
        .pmkt_panel_blank h2 { font-size: 17px; font-weight: 600; margin: 0; }
        .pmkt_panel_blank p  { font-size: 13px; color: var(--color-text-secondary); margin: 0; max-width: 220px; line-height: 1.6; }

        /* ── Detail view ── */
        .pmkt_detail { display: flex; flex-direction: column; height: 100%; }

        /* Header */
        .pmkt_detail_hd {
            padding: 18px 24px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex; align-items: flex-start; justify-content: space-between;
            gap: 12px;
        }
        .pmkt_detail_hd_left { min-width: 0; }
        .pmkt_detail_eyebrow {
            font-size: 10px; font-weight: 600;
            letter-spacing: .09em; text-transform: uppercase;
            color: var(--color-text-tertiary);
            margin: 0 0 5px; display: flex; align-items: center; gap: 6px;
        }
        .pmkt_detail_title {
            font-family: var(--font-serif);
            font-size: 24px; font-weight: 600;
            color: var(--color-text-primary);
            margin: 0 0 6px;
        }
        .pmkt_detail_sub {
            display: flex; align-items: center; gap: 10px; flex-wrap: wrap;
        }
        .pmkt_zone_badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 3px 10px;
            border-radius: 999px;
            font-size: 11px; font-weight: 500;
            border: 0.5px solid;
        }
        .pmkt_zone_badge--west   { background: var(--zone-west-bg); color: var(--zone-west); border-color: rgba(38,104,168,.2); }
        .pmkt_zone_badge--middle { background: var(--zone-mid-bg);  color: var(--zone-mid);  border-color: rgba(74,124,63,.2); }
        .pmkt_zone_badge--east   { background: var(--zone-east-bg); color: var(--zone-east); border-color: rgba(200,134,10,.2); }
        .pmkt_zone_badge i { font-size: 11px; }

        /* Stats row */
        .pmkt_stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            border-bottom: 0.5px solid var(--color-border-tertiary);
        }
        .pmkt_stat {
            padding: 14px 20px;
            border-right: 0.5px solid var(--color-border-tertiary);
        }
        .pmkt_stat:last-child { border-right: none; }
        .pmkt_stat_lbl {
            font-size: 10px; text-transform: uppercase; letter-spacing: .06em;
            color: var(--color-text-tertiary); display: block; margin-bottom: 5px;
        }
        .pmkt_stat_val {
            font-size: 20px; font-weight: 600;
            color: var(--color-text-primary);
            display: flex; align-items: baseline; gap: 3px;
            line-height: 1;
        }
        .pmkt_stat_val--sm { font-size: 13px; align-items: center; }
        .pmkt_stat_unit { font-size: 12px; color: var(--color-text-secondary); font-weight: 400; }

        /* Body: two-column grid */
        .pmkt_body {
            display: grid;
            grid-template-columns: 1fr 200px;
            gap: 0;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            flex: 1;
        }
        .pmkt_body_left {
            padding: 18px 24px;
            border-right: 0.5px solid var(--color-border-tertiary);
            display: flex; flex-direction: column; gap: 20px;
        }
        .pmkt_body_right {
            padding: 18px 16px;
            display: flex; flex-direction: column; gap: 16px;
        }

        /* Sun schedule */
        .pmkt_sun_section {}
        .pmkt_sun_section_label {
            font-size: 10px; font-weight: 600; letter-spacing: .07em;
            text-transform: uppercase; color: var(--color-text-tertiary);
            margin: 0 0 10px; display: block;
        }
        .pmkt_sun_slots {
            display: flex; flex-direction: column; gap: 6px;
        }
        .pmkt_sun_slot {
            display: flex; align-items: center; gap: 10px;
        }
        .pmkt_sun_slot_time {
            font-size: 11px; color: var(--color-text-tertiary);
            width: 72px; flex-shrink: 0;
        }
        .pmkt_sun_bar_wrap {
            flex: 1; height: 6px;
            background: var(--color-border-tertiary);
            border-radius: 3px; overflow: hidden;
        }
        .pmkt_sun_bar {
            height: 100%; border-radius: 3px;
            transition: width .4s ease;
        }
        .pmkt_sun_bar--full    { background: linear-gradient(90deg, #f59e0b, #ef4444); }
        .pmkt_sun_bar--partial { background: linear-gradient(90deg, var(--plot_moss), #f59e0b); }
        .pmkt_sun_bar--shade   { background: var(--color-border-tertiary); }
        .pmkt_sun_slot_icon { font-size: 13px; flex-shrink: 0; }

        /* Dimensions visual */
        .pmkt_footprint {}
        .pmkt_footprint_label {
            font-size: 10px; font-weight: 600; letter-spacing: .07em;
            text-transform: uppercase; color: var(--color-text-tertiary);
            margin: 0 0 10px; display: block;
        }
        .pmkt_footprint_svg_wrap {
            display: flex; align-items: center; justify-content: center;
            padding: 10px 0;
        }
        .pmkt_footprint_dims {
            display: flex; justify-content: space-between;
            font-size: 11px; color: var(--color-text-tertiary);
            margin-top: 6px;
        }

        /* Neighbor count */
        .pmkt_neighbors_row {
            display: flex; align-items: center; gap: 8px;
            padding: 10px 12px;
            background: var(--plot_moss_bg);
            border: 0.5px solid var(--plot_border);
            border-radius: var(--border-radius-md);
            font-size: 12px; color: var(--plot_moss);
        }
        .pmkt_neighbors_row i { font-size: 14px; }

        /* Notes */
        .pmkt_notes {
            padding: 14px 24px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
        }
        .pmkt_notes_label {
            font-size: 10px; font-weight: 600; letter-spacing: .07em;
            text-transform: uppercase; color: var(--color-text-tertiary);
            margin: 0 0 8px; display: block;
        }
        .pmkt_notes_text {
            font-size: 13px; color: var(--color-text-secondary);
            line-height: 1.6; margin: 0;
        }

        /* CTA footer */
        .pmkt_cta {
            padding: 16px 24px;
            background: var(--color-background-secondary);
            border-top: 0.5px solid var(--color-border-tertiary);
            display: flex; align-items: center; gap: 16px;
            margin-top: auto;
        }
        .pmkt_apply_btn {
            height: 44px; padding: 0 28px;
            border: none; border-radius: var(--border-radius-md);
            background: var(--plot_moss); color: #fff;
            font-size: 14px; font-weight: 500; cursor: pointer;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s, transform .1s;
        }
        .pmkt_apply_btn:hover { background: var(--plot_moss_mid); transform: translateY(-1px); }
        .pmkt_apply_btn:active { transform: none; }
        .pmkt_cta_hint { font-size: 12px; color: var(--color-text-tertiary); margin: 0; line-height: 1.5; }

        /* ── Modal enhancements ── */
        .pmkt_modal_plot_card {
            margin: 0 0 4px;
            padding: 12px 14px;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            background: var(--color-background-secondary);
            display: flex; align-items: center; justify-content: space-between;
            gap: 10px;
        }
        .pmkt_modal_plot_name { font-size: 14px; font-weight: 600; color: var(--color-text-primary); }
        .pmkt_modal_plot_meta { font-size: 12px; color: var(--color-text-tertiary); margin-top: 2px; }

        /* Responsive */
        @media (max-width: 860px) {
            .pmkt_layout { grid-template-columns: 1fr; }
            .pmkt_stats  { grid-template-columns: repeat(2, 1fr); }
            .pmkt_body   { grid-template-columns: 1fr; }
            .pmkt_body_left { border-right: none; border-bottom: 0.5px solid var(--color-border-tertiary); }
            .pmkt_hero_pills { display: none; }
        }
        @media (max-width: 540px) {
            .pmkt_stats { grid-template-columns: 1fr 1fr; }
        }
    </style>
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

{{-- Hero --}}
<div class="pmkt_hero">
    <div class="pmkt_hero_left">
        <h1>Plot Market</h1>
        <p>Browse available allotment plots and submit a rental application.</p>
    </div>
    <div class="pmkt_hero_pills">
        <span class="pmkt_pill pmkt_pill--green">
            <i class="ti ti-plant"></i>
            {{ $plots->where('status','available')->count() }} Available
        </span>
        <span class="pmkt_pill pmkt_pill--amber">
            <i class="ti ti-users"></i>
            {{ $plots->where('status','rented')->count() }} Rented
        </span>
    </div>
</div>

<div class="pmkt_layout">

    {{-- ── SIDEBAR ── --}}
    <aside class="pmkt_sidebar">
        <div class="pmkt_sidebar_head">
            <p class="pmkt_sidebar_title">Available Plots</p>

            <div class="pmkt_search_wrap">
                <i class="ti ti-search"></i>
                <input id="pmkt_search" class="pmkt_search" type="search" placeholder="Search by ID or zone…">
            </div>

            <div class="pmkt_tabs">
                <button class="pmkt_tab active" data-zone="all">All</button>
                <button class="pmkt_tab" data-zone="west">
                    <span style="display:flex;align-items:center;gap:4px">
                        <span class="pmkt_zone_dot pmkt_zone_dot--west"></span> West
                    </span>
                </button>
                <button class="pmkt_tab" data-zone="middle">
                    <span style="display:flex;align-items:center;gap:4px">
                        <span class="pmkt_zone_dot pmkt_zone_dot--middle"></span> Mid
                    </span>
                </button>
                <button class="pmkt_tab" data-zone="east">
                    <span style="display:flex;align-items:center;gap:4px">
                        <span class="pmkt_zone_dot pmkt_zone_dot--east"></span> East
                    </span>
                </button>
            </div>
        </div>

        <ul class="pmkt_list" id="pmkt_list">
            @forelse($plots as $plot)
                @php
                    // sun_profile stores 'west'|'center'|'east' (set by PlotGeneratorService)
                    $zoneKey  = match($plot->sun_profile ?? 'center') {
                        'west'  => 'west',
                        'east'  => 'east',
                        default => 'middle',  // 'center' → JS key 'middle'
                    };
                    $zoneLabel = match($zoneKey) { 'west' => 'West Wing', 'east' => 'East Wing', default => 'Central' };
                    $zoneIcon  = match($zoneKey) { 'west' => 'ti-sun-low', 'east' => 'ti-sun-high', default => 'ti-sun' };
                @endphp
                <li class="pmkt_item"
                    data-plot='@json($plot)'
                    data-zone="{{ $zoneKey }}"
                    data-size="{{ $plot->size }}"
                    tabindex="0">

                    <div class="pmkt_item_top">
                        <span class="pmkt_item_name">Plot #{{ $plot->id }}</span>
                        <span class="pmkt_zone_badge pmkt_zone_badge--{{ $zoneKey }}">
                            <i class="ti {{ $zoneIcon }}"></i>
                            {{ $zoneLabel }}
                        </span>
                    </div>

                    <div class="pmkt_item_bottom">
                        <span class="pmkt_item_dims">
                            <i class="ti ti-dimensions"></i>
                            {{ $plot->width }}×{{ $plot->height }} m
                        </span>
                        <span class="pmkt_size_chip">{{ $plot->size }}</span>
                    </div>

                </li>
            @empty
                <li class="pmkt_empty">
                    <i class="ti ti-plant-off"></i>
                    <p>No plots available right now.</p>
                </li>
            @endforelse
        </ul>
    </aside>


    {{-- ── RIGHT PANEL ── --}}
    <div class="pmkt_panel">

        {{-- Empty state --}}
        <div class="pmkt_panel_blank" id="pmkt_blank">
            <div class="pmkt_panel_blank_icon">
                <i class="ti ti-map-2"></i>
            </div>
            <h2>Select a Plot</h2>
            <p>Choose a plot from the list to view its full details and apply for a rental.</p>
        </div>

        {{-- Detail --}}
        <div class="pmkt_detail" id="pmkt_detail" hidden>

            {{-- Header --}}
            <div class="pmkt_detail_hd">
                <div class="pmkt_detail_hd_left">
                    <p class="pmkt_detail_eyebrow">
                        <span class="pmkt_zone_dot" id="d_zone_dot" style="width:8px;height:8px;border-radius:50%;display:inline-block;"></span>
                        <span id="d_zone_label">—</span>
                    </p>
                    <h2 class="pmkt_detail_title" id="d_title">—</h2>
                    <div class="pmkt_detail_sub">
                        <span class="plot_badge plot_badge--available" id="d_status_badge">Available</span>
                        <span class="plot_badge" id="d_size_badge" style="background:var(--color-background-secondary);color:var(--color-text-secondary);">—</span>
                    </div>
                </div>
            </div>

            {{-- Stats --}}
            <div class="pmkt_stats">
                <div class="pmkt_stat">
                    <span class="pmkt_stat_lbl">Area</span>
                    <span class="pmkt_stat_val"><span id="d_area">—</span><span class="pmkt_stat_unit">m²</span></span>
                </div>
                <div class="pmkt_stat">
                    <span class="pmkt_stat_lbl">Dimensions</span>
                    <span class="pmkt_stat_val pmkt_stat_val--sm" id="d_dims">—</span>
                </div>
                <div class="pmkt_stat">
                    <span class="pmkt_stat_lbl">Sun Profile</span>
                    <span class="pmkt_stat_val pmkt_stat_val--sm" id="d_sun_profile">—</span>
                </div>
                <div class="pmkt_stat">
                    <span class="pmkt_stat_lbl">Soil Quality</span>
                    <span class="pmkt_stat_val pmkt_stat_val--sm" id="d_soil">—</span>
                </div>
            </div>

            {{-- Body --}}
            <div class="pmkt_body">

                {{-- Left: sun schedule + notes --}}
                <div class="pmkt_body_left">

                    {{-- Sun schedule --}}
                    <div class="pmkt_sun_section">
                        <span class="pmkt_sun_section_label">Daily Sunlight Schedule</span>
                        <div class="pmkt_sun_slots" id="d_sun_slots">
                            {{-- filled by JS --}}
                        </div>
                    </div>

                    {{-- Neighbor info --}}
                    <div class="pmkt_neighbors_row" id="d_neighbors_row" style="display:none">
                        <i class="ti ti-layout-grid"></i>
                        <span id="d_neighbors_text">—</span>
                    </div>

                    {{-- Notes --}}
                    <div id="d_notes_wrap" style="display:none">
                        <span class="pmkt_sun_section_label">Notes</span>
                        <p class="pmkt_notes_text" id="d_notes"></p>
                    </div>

                </div>

                {{-- Right: footprint visualizer --}}
                <div class="pmkt_body_right">
                    <div class="pmkt_footprint">
                        <span class="pmkt_footprint_label">Plot Footprint</span>
                        <div class="pmkt_footprint_svg_wrap">
                            <svg id="d_footprint_svg" width="120" height="140" viewBox="0 0 120 140">
                                <rect id="d_footprint_rect"
                                      fill="var(--plot_moss_bg)"
                                      stroke="var(--plot_moss)"
                                      stroke-width="1.5"
                                      rx="3"/>
                                <text id="d_footprint_lbl"
                                      x="60" y="75"
                                      text-anchor="middle"
                                      dominant-baseline="middle"
                                      font-size="11"
                                      fill="var(--plot_moss)"
                                      font-family="var(--font-sans)">—</text>
                            </svg>
                        </div>
                        <div class="pmkt_footprint_dims">
                            <span id="d_fp_w">—</span>
                            <span id="d_fp_h">—</span>
                        </div>
                    </div>

                    {{-- Compass zone card --}}
                    <div id="d_compass_card" style="padding:10px 12px;border:0.5px solid var(--color-border-tertiary);border-radius:var(--border-radius-md);background:var(--color-background-secondary);">
                        <span style="font-size:10px;font-weight:600;letter-spacing:.07em;text-transform:uppercase;color:var(--color-text-tertiary);display:block;margin-bottom:8px;">Position</span>
                        {{-- Mini compass SVG --}}
                        <svg viewBox="0 0 80 80" width="80" style="display:block;margin:0 auto 8px;">
                            <circle cx="40" cy="40" r="36" fill="none" stroke="var(--color-border-tertiary)" stroke-width="1"/>
                            <text x="40" y="10" text-anchor="middle" font-size="8" fill="var(--color-text-tertiary)" font-family="var(--font-sans)">N</text>
                            <text x="40" y="74" text-anchor="middle" font-size="8" fill="var(--color-text-tertiary)" font-family="var(--font-sans)">S</text>
                            <text x="6"  y="43" text-anchor="middle" font-size="8" fill="var(--color-text-tertiary)" font-family="var(--font-sans)">W</text>
                            <text x="74" y="43" text-anchor="middle" font-size="8" fill="var(--color-text-tertiary)" font-family="var(--font-sans)">E</text>
                            <circle id="d_compass_dot" cx="40" cy="40" r="7" fill="var(--plot_moss)" opacity=".85"/>
                        </svg>
                        <p id="d_compass_label" style="font-size:11px;color:var(--color-text-secondary);text-align:center;margin:0;">—</p>
                    </div>
                </div>
            </div>

            {{-- CTA --}}
            <div class="pmkt_cta">
                <button class="pmkt_apply_btn" id="pmkt_open_modal">
                    <i class="ti ti-clipboard-plus"></i>
                    Apply for this Plot
                </button>
                <p class="pmkt_cta_hint">Applications are reviewed<br>by the garden warden.</p>
            </div>

        </div>
    </div>

</div>{{-- /.pmkt_layout --}}


{{-- ═══════════════════════════════════════
     APPLY MODAL
═══════════════════════════════════════ --}}
<div id="pmkt_modal" class="plot_modal_backdrop" style="display:none;" aria-modal="true" role="dialog">
    <div class="plot_modal">

        <div class="plot_modal_header">
            <div>
                <p class="plot_section_label">Rental Application</p>
                <h3 class="plot_modal_title">Apply for <span id="modal_plot_name"></span></h3>
            </div>
            <button class="plot_modal_close" onclick="pmktCloseModal()" aria-label="Close">
                <i class="ti ti-x"></i>
            </button>
        </div>

        <form method="POST" action="{{ route('rental.store') }}" class="plot_modal_form">
            @csrf
            <input type="hidden" name="plot_id" id="modal_plot_id">

            {{-- Plot summary card inside modal --}}
            <div class="pmkt_modal_plot_card" id="modal_plot_card">
                <div>
                    <div class="pmkt_modal_plot_name" id="modal_card_name">—</div>
                    <div class="pmkt_modal_plot_meta" id="modal_card_meta">—</div>
                </div>
                <span class="plot_badge plot_badge--available">Available</span>
            </div>

            {{-- Message --}}
            <div class="plot_field">
                <label class="plot_label" for="modal_message">
                    Why do you want this plot? <span>*</span>
                </label>
                <textarea name="message" id="modal_message"
                          class="plot_input plot_textarea"
                          rows="3"
                          placeholder="Tell the warden about your plans…"></textarea>
            </div>

            {{-- Experience --}}
            <div class="plot_field">
                <label class="plot_label" for="modal_experience">Gardening Experience</label>
                <select name="experience_level" id="modal_experience" class="plot_input plot_select">
                    <option value="beginner">Beginner — just getting started</option>
                    <option value="intermediate">Intermediate — a few seasons in</option>
                    <option value="experienced">Experienced — green thumbs only</option>
                </select>
            </div>

            {{-- Sharing --}}
            <div class="plot_field">
                <label class="plot_label">Would you like to share this plot?</label>
                <div class="plot_radio_group">
                    <label class="plot_radio_option">
                        <input type="radio" name="share" value="1" checked>
                        <span class="plot_radio_card">
                            <i class="ti ti-user"></i> Solo
                        </span>
                    </label>
                    <label class="plot_radio_option">
                        <input type="radio" name="share" value="0.5">
                        <span class="plot_radio_card">
                            <i class="ti ti-users"></i> Shared
                        </span>
                    </label>
                </div>
            </div>

            <div class="plot_modal_footer">
                <button type="button" class="plot_cancel_btn" onclick="pmktCloseModal()">Cancel</button>
                <button type="submit" class="plot_submit_btn">
                    <i class="ti ti-send"></i>
                    Submit Application
                </button>
            </div>
        </form>
    </div>
</div>

@endsection


@push('scripts')
<script>
/* ═══════════════════════════════════════════════════════════
   SUN SCHEDULE DATA
   Mirrors your Python SUN_SCHEDULE dict — edit to match yours
═══════════════════════════════════════════════════════════ */
const SUN_SCHEDULE = {
    west: [
        { time: 'Morning (6–10)', level: 'shade',   icon: '🌥️', pct: 15 },
        { time: 'Midday (10–14)', level: 'partial',  icon: '⛅', pct: 50 },
        { time: 'Afternoon (14–18)', level: 'full',  icon: '☀️', pct: 90 },
        { time: 'Evening (18–20)', level: 'partial', icon: '🌇', pct: 55 },
    ],
    middle: [
        { time: 'Morning (6–10)', level: 'partial',  icon: '⛅', pct: 45 },
        { time: 'Midday (10–14)', level: 'full',     icon: '☀️', pct: 95 },
        { time: 'Afternoon (14–18)', level: 'full',  icon: '☀️', pct: 85 },
        { time: 'Evening (18–20)', level: 'partial', icon: '🌇', pct: 40 },
    ],
    east: [
        { time: 'Morning (6–10)', level: 'full',    icon: '🌅', pct: 90 },
        { time: 'Midday (10–14)', level: 'full',    icon: '☀️', pct: 80 },
        { time: 'Afternoon (14–18)', level: 'partial', icon: '⛅', pct: 40 },
        { time: 'Evening (18–20)', level: 'shade',  icon: '🌥️', pct: 10 },
    ],
};

/* Zone metadata */
const ZONE_META = {
    west:   { label: 'West Wing',  dotClass: 'pmkt_zone_dot--west',   compassX: 18, compassY: 40, compassDesc: 'Western third — afternoon sun' },
    middle: { label: 'Central',    dotClass: 'pmkt_zone_dot--middle',  compassX: 40, compassY: 40, compassDesc: 'Central — balanced sunlight' },
    east:   { label: 'East Wing',  dotClass: 'pmkt_zone_dot--east',    compassX: 62, compassY: 40, compassDesc: 'Eastern third — morning sun' },
};

/* Sun profile label from zone */
const SUN_LABEL = { west: 'Afternoon Sun', middle: 'Full Sun', east: 'Morning Sun' };

/* ── State ─────────────────────────────────────────────── */
let activePlot = null;

/* ── Helpers ────────────────────────────────────────────── */
function zoneKey(plot) {
    // sun_profile from DB: 'west'|'center'|'east' — map 'center' to JS key 'middle'
    const raw = (plot.sun_profile ?? 'center').toLowerCase();
    return raw === 'east' ? 'east' : raw === 'west' ? 'west' : 'middle';
}

function renderSunSlots(zone) {
    const slots = SUN_SCHEDULE[zone] ?? SUN_SCHEDULE.middle;
    return slots.map(s => `
        <div class="pmkt_sun_slot">
            <span class="pmkt_sun_slot_time">${s.time}</span>
            <div class="pmkt_sun_bar_wrap">
                <div class="pmkt_sun_bar pmkt_sun_bar--${s.level}" style="width:${s.pct}%"></div>
            </div>
            <span class="pmkt_sun_slot_icon">${s.icon}</span>
        </div>
    `).join('');
}

function renderFootprint(w, h) {
    // Scale to fit 120×140 viewBox with 10px padding
    const maxW = 100, maxH = 120;
    const aspect = w / h;
    let rw, rh;
    if (aspect > maxW / maxH) { rw = maxW; rh = maxW / aspect; }
    else                       { rh = maxH; rw = maxH * aspect; }
    const rx = (120 - rw) / 2, ry = (140 - rh) / 2;

    document.getElementById('d_footprint_rect').setAttribute('x', rx);
    document.getElementById('d_footprint_rect').setAttribute('y', ry);
    document.getElementById('d_footprint_rect').setAttribute('width',  rw);
    document.getElementById('d_footprint_rect').setAttribute('height', rh);

    const lbl = document.getElementById('d_footprint_lbl');
    lbl.setAttribute('x', rx + rw / 2);
    lbl.setAttribute('y', ry + rh / 2);
    lbl.textContent = `${w}×${h}`;
}

/* ── Select a plot ─────────────────────────────────────── */
function selectPlot(el) {
    document.querySelectorAll('.pmkt_item').forEach(i => i.classList.remove('active'));
    el.classList.add('active');

    activePlot = JSON.parse(el.dataset.plot);
    const zk   = zoneKey(activePlot);
    const zm   = ZONE_META[zk];

    document.getElementById('pmkt_blank').style.display = 'none';
    document.getElementById('pmkt_detail').hidden       = false;

    // Header
    const dot = document.getElementById('d_zone_dot');
    dot.className = 'pmkt_zone_dot ' + zm.dotClass;
    document.getElementById('d_zone_label').textContent = zm.label;
    document.getElementById('d_title').textContent      = `Plot #${activePlot.id}`;

    // Status badge
    const statusBadge = document.getElementById('d_status_badge');
    statusBadge.textContent = activePlot.status ?? 'Available';
    statusBadge.className   = 'plot_badge plot_badge--' + (activePlot.status ?? 'available');

    // Size badge
    document.getElementById('d_size_badge').textContent = activePlot.size ?? '—';

    // Stats
    document.getElementById('d_area').textContent        = activePlot.area ?? (activePlot.width * activePlot.height) ?? '—';
    document.getElementById('d_dims').textContent        = `${activePlot.width ?? '?'}×${activePlot.height ?? '?'} m`;
    document.getElementById('d_sun_profile').textContent = SUN_LABEL[zk] ?? '—';
    document.getElementById('d_soil').textContent        = activePlot.soil_quality ?? '—';

    // Sun slots
    document.getElementById('d_sun_slots').innerHTML = renderSunSlots(zk);

    // Footprint
    renderFootprint(activePlot.width ?? 10, activePlot.height ?? 10);
    document.getElementById('d_fp_w').textContent = `W: ${activePlot.width ?? '?'} m`;
    document.getElementById('d_fp_h').textContent = `H: ${activePlot.height ?? '?'} m`;

    // Compass
    document.getElementById('d_compass_dot').setAttribute('cx', zm.compassX);
    document.getElementById('d_compass_dot').setAttribute('cy', zm.compassY);
    document.getElementById('d_compass_label').textContent = zm.compassDesc;

    // Neighbors
    const neighborCount = activePlot.neighbors_count ?? (activePlot.neighbors?.length ?? null);
    const nRow = document.getElementById('d_neighbors_row');
    if (neighborCount !== null) {
        nRow.style.display = 'flex';
        document.getElementById('d_neighbors_text').textContent =
            `${neighborCount} neighboring plot${neighborCount !== 1 ? 's' : ''} — check with the warden for shared path access.`;
    } else {
        nRow.style.display = 'none';
    }

    // Notes
    const notesWrap = document.getElementById('d_notes_wrap');
    if (activePlot.notes) {
        document.getElementById('d_notes').textContent = activePlot.notes;
        notesWrap.style.display = 'block';
    } else {
        notesWrap.style.display = 'none';
    }
}

/* ── List item events ──────────────────────────────────── */
document.querySelectorAll('.pmkt_item').forEach(el => {
    el.addEventListener('click', () => selectPlot(el));
    el.addEventListener('keydown', e => { if (e.key === 'Enter' || e.key === ' ') selectPlot(el); });
});

/* ── Search ────────────────────────────────────────────── */
document.getElementById('pmkt_search').addEventListener('input', e => {
    const q = e.target.value.toLowerCase();
    document.querySelectorAll('.pmkt_item').forEach(el => {
        el.style.display = el.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
});

/* ── Zone tabs ─────────────────────────────────────────── */
document.querySelectorAll('.pmkt_tab').forEach(btn => {
    btn.addEventListener('click', () => {
        document.querySelectorAll('.pmkt_tab').forEach(b => b.classList.remove('active'));
        btn.classList.add('active');

        const filter = btn.dataset.zone;
        document.querySelectorAll('.pmkt_item').forEach(el => {
            el.style.display = (filter === 'all' || el.dataset.zone === filter) ? '' : 'none';
        });
    });
});

/* ── Modal ─────────────────────────────────────────────── */
function pmktOpenModal() {
    if (!activePlot) return;
    const zk = zoneKey(activePlot);
    document.getElementById('modal_plot_id').value         = activePlot.id;
    document.getElementById('modal_plot_name').textContent = `Plot #${activePlot.id}`;
    document.getElementById('modal_card_name').textContent = `Plot #${activePlot.id}`;
    document.getElementById('modal_card_meta').textContent =
        `${ZONE_META[zk].label} · ${activePlot.size ?? '—'} · ${activePlot.width ?? '?'}×${activePlot.height ?? '?'} m`;
    document.getElementById('pmkt_modal').style.display    = 'flex';
    document.body.style.overflow = 'hidden';
}
function pmktCloseModal() {
    document.getElementById('pmkt_modal').style.display = 'none';
    document.body.style.overflow = '';
}

document.getElementById('pmkt_open_modal').addEventListener('click', pmktOpenModal);
document.getElementById('pmkt_modal').addEventListener('click', e => { if (e.target === e.currentTarget) pmktCloseModal(); });
document.addEventListener('keydown', e => { if (e.key === 'Escape') pmktCloseModal(); });
</script>
@endpush