@extends('layouts.app')

@section('title', 'Admin Dashboard')

@push('styles')
    <style>
        /* ══════════════════════════════════════════════════════════
           ADMIN DASHBOARD  ·  dashboard/admin.blade.php
           Prefix: adash_
        ══════════════════════════════════════════════════════════ */

        :root {
            --adash_green:       #3d7a2f;
            --adash_green_mid:   #5a9e48;
            --adash_green_light: #e2eeda;
            --adash_green_bg:    #f1f7ec;
            --adash_green_border:rgba(61,122,47,.18);

            --adash_sky:         #1d6ea8;
            --adash_sky_bg:      #e8f2fb;
            --adash_amber:       #b45309;
            --adash_amber_bg:    #fef3c7;
            --adash_red:         #7b1f1f;
            --adash_red_bg:      #fceaea;
            --adash_purple:      #5b21b6;
            --adash_purple_bg:   #f5f3ff;
        }

        /* ── Layout ── */
        .adash_layout { display: flex; flex-direction: column; gap: 20px; }

        /* ── Header ── */
        .adash_hero {
            display: flex;
            align-items: flex-start;
            justify-content: space-between;
            gap: 16px;
            padding-bottom: 20px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            flex-wrap: wrap;
        }
        .adash_hero_title {
            font-family: var(--font-serif);
            font-size: 28px;
            font-weight: 500;
            margin: 0 0 4px;
        }
        .adash_hero_sub { font-size: 13px; color: var(--color-text-secondary); margin: 0; }

        .adash_season_card {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 16px;
            background: var(--adash_green_bg);
            border: 0.5px solid var(--adash_green_border);
            border-radius: var(--border-radius-md);
            flex-shrink: 0;
        }
        .adash_season_label { font-size: 10px; text-transform: uppercase; letter-spacing: .07em; color: var(--adash_green); display: block; }
        .adash_season_name  { font-size: 14px; font-weight: 600; color: var(--adash_green); }

        /* ── Top stats strip ── */
        .adash_stats_strip {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 12px;
        }
        .adash_strip_stat {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            padding: 14px 16px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .adash_strip_stat_icon {
            width: 30px; height: 30px;
            border-radius: 7px;
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 8px;
            flex-shrink: 0;
        }
        .adash_strip_stat_icon i { font-size: 15px; }
        .adash_strip_stat_icon--green  { background: var(--adash_green_light); color: var(--adash_green); }
        .adash_strip_stat_icon--sky    { background: var(--adash_sky_bg);      color: var(--adash_sky); }
        .adash_strip_stat_icon--amber  { background: var(--adash_amber_bg);    color: var(--adash_amber); }
        .adash_strip_stat_icon--red    { background: var(--adash_red_bg);      color: var(--adash_red); }
        .adash_strip_stat_icon--purple { background: var(--adash_purple_bg);   color: var(--adash_purple); }

        .adash_strip_stat_label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: var(--color-text-tertiary); }
        .adash_strip_stat_value { font-size: 26px; font-weight: 500; color: var(--color-text-primary); line-height: 1; }
        .adash_strip_stat_sub   { font-size: 11px; color: var(--color-text-secondary); margin-top: 1px; }

        /* ── Body grid ── */
        .adash_body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 16px;
            align-items: start;
        }
        .adash_left  { display: flex; flex-direction: column; gap: 16px; }
        .adash_right { display: flex; flex-direction: column; gap: 16px; }

        /* ── Module cards ── */
        .adash_card {
            background: var(--color-background-primary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-lg);
            overflow: hidden;
        }
        .adash_card_header {
            padding: 14px 18px;
            border-bottom: 0.5px solid var(--color-border-tertiary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .adash_card_header_left { display: flex; align-items: center; gap: 10px; }
        .adash_card_icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .adash_card_icon i { font-size: 15px; }
        .adash_card_icon--green  { background: var(--adash_green_light); color: var(--adash_green); }
        .adash_card_icon--sky    { background: var(--adash_sky_bg);      color: var(--adash_sky); }
        .adash_card_icon--amber  { background: var(--adash_amber_bg);    color: var(--adash_amber); }
        .adash_card_icon--red    { background: var(--adash_red_bg);      color: var(--adash_red); }
        .adash_card_icon--purple { background: var(--adash_purple_bg);   color: var(--adash_purple); }

        .adash_card_title    { font-family: var(--font-serif); font-size: 17px; font-weight: 500; margin: 0; }
        .adash_card_subtitle { font-size: 11px; color: var(--color-text-tertiary); margin: 0; }

        .adash_card_body { padding: 16px 18px; display: flex; flex-direction: column; gap: 14px; }
        .adash_card_footer {
            padding: 10px 18px;
            border-top: 0.5px solid var(--color-border-tertiary);
            background: var(--color-background-secondary);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        /* ── Inline stat grid inside card ── */
        .adash_mini_stats {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        .adash_mini_stats--2 { grid-template-columns: repeat(2, 1fr); }
        .adash_mini_stats--4 { grid-template-columns: repeat(4, 1fr); }

        .adash_mini_stat {
            padding: 10px 12px;
            background: var(--color-background-secondary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
        }
        .adash_mini_stat_label { font-size: 10px; text-transform: uppercase; letter-spacing: .06em; color: var(--color-text-tertiary); display: block; margin-bottom: 4px; }
        .adash_mini_stat_value { font-size: 20px; font-weight: 500; color: var(--color-text-primary); line-height: 1; }
        .adash_mini_stat_sub   { font-size: 10px; color: var(--color-text-tertiary); margin-top: 2px; display: block; }

        /* ── Health bars ── */
        .adash_health_row { display: flex; flex-direction: column; gap: 5px; }
        .adash_health_labels { display: flex; align-items: center; justify-content: space-between; }
        .adash_health_title  { font-size: 12px; color: var(--color-text-secondary); }
        .adash_health_pct    { font-size: 12px; font-weight: 600; color: var(--color-text-primary); }
        .adash_health_bar {
            height: 5px;
            background: var(--color-border-tertiary);
            border-radius: 3px;
            overflow: hidden;
        }
        .adash_health_fill { height: 100%; border-radius: 3px; transition: width .4s ease; }
        .adash_health_fill--green  { background: var(--adash_green); }
        .adash_health_fill--sky    { background: var(--adash_sky); }
        .adash_health_fill--amber  { background: var(--adash_amber); }
        .adash_health_fill--red    { background: var(--adash_red); }
        .adash_health_fill--purple { background: var(--adash_purple); }

        /* ── Link ── */
        .adash_link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 500;
            color: var(--adash_green);
            text-decoration: none;
            transition: gap 0.15s;
        }
        .adash_link:hover { gap: 8px; }

        /* ── Alert row ── */
        .adash_alert_row {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 9px 12px;
            border-radius: var(--border-radius-md);
            font-size: 12px;
            border: 0.5px solid;
        }
        .adash_alert_row i { font-size: 14px; flex-shrink: 0; }
        .adash_alert_row--red    { background: var(--adash_red_bg);    color: var(--adash_red);    border-color: rgba(123,31,31,.2); }
        .adash_alert_row--amber  { background: var(--adash_amber_bg);  color: var(--adash_amber);  border-color: rgba(180,83,9,.2); }
        .adash_alert_row--green  { background: var(--adash_green_bg);  color: var(--adash_green);  border-color: var(--adash_green_border); }

        /* ── Badge ── */
        .adash_badge {
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
        }
        .adash_badge--green  { background: var(--adash_green_light); color: #24521a; }
        .adash_badge--amber  { background: var(--adash_amber_bg);    color: var(--adash_amber); }
        .adash_badge--red    { background: var(--adash_red_bg);      color: var(--adash_red); }
        .adash_badge--sky    { background: var(--adash_sky_bg);      color: var(--adash_sky); }
        .adash_badge--purple { background: var(--adash_purple_bg);   color: var(--adash_purple); }

        /* ── Action button ── */
        .adash_action_btn {
            height: 36px;
            padding: 0 16px;
            border: none;
            border-radius: var(--border-radius-md);
            background: var(--adash_green);
            color: #fff;
            font-size: 12px;
            font-weight: 500;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            transition: background 0.15s;
            white-space: nowrap;
        }
        .adash_action_btn:hover { background: var(--adash_green_mid); }

        /* ── Recent applications list ── */
        .adash_app_list { display: flex; flex-direction: column; gap: 8px; }
        .adash_app_item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: var(--color-background-secondary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: var(--border-radius-md);
            gap: 10px;
        }
        .adash_app_name { font-size: 13px; font-weight: 500; color: var(--color-text-primary); margin: 0 0 2px; }
        .adash_app_meta { font-size: 11px; color: var(--color-text-tertiary); margin: 0; }

        /* Allocation card */
        .adash_allocation_card {
            background: var(--adash_green_bg);
            border: 0.5px solid var(--adash_green_border);
            border-radius: var(--border-radius-lg);
            padding: 18px;
        }
        .adash_allocation_title { font-family: var(--font-serif); font-size: 16px; font-weight: 500; margin: 0 0 5px; }
        .adash_allocation_desc  { font-size: 12px; color: var(--color-text-secondary); margin: 0 0 14px; line-height: 1.5; }
        .adash_allocation_btn {
            width: 100%;
            height: 40px;
            border: none;
            border-radius: var(--border-radius-md);
            background: var(--adash_green);
            color: #fff;
            font-size: 13px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: background 0.15s;
        }
        .adash_allocation_btn:hover { background: var(--adash_green_mid); }

        /* Responsive */
        @media (max-width: 1024px) {
            .adash_stats_strip { grid-template-columns: repeat(3, 1fr); }
            .adash_body        { grid-template-columns: 1fr; }
        }
        @media (max-width: 640px) {
            .adash_stats_strip { grid-template-columns: repeat(2, 1fr); }
            .adash_mini_stats--4 { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
@endpush

@section('content')

@php
    $plotOccupancy = $plotStats['total'] > 0
        ? round(($plotStats['rented'] / $plotStats['total']) * 100) : 0;
    $toolUsage = $toolStats['total'] > 0
        ? round(($toolStats['in_use'] / $toolStats['total']) * 100) : 0;
@endphp

{{-- Alerts --}}
  @if(session('message'))
  <div>
    <i></i>
    <span>{{ session('message') }}</span>
  </div>
  @endif

  @if(session('error'))
  <div>
    <i></i>
    <span>{{ session('error') }}</span>
  </div>
  @endif

  @if($errors->any())
  <div>
    <i></i>
    <span>{{ $errors->first() }}</span>
  </div>
  @endif


<div class="adash_layout">

    {{-- ══════════════════════════════════════════════
         HERO
    ══════════════════════════════════════════════ --}}
    <div class="adash_hero">
        <div>
            <p style="font-size:11px;font-weight:500;letter-spacing:.08em;text-transform:uppercase;color:var(--color-text-tertiary);margin:0 0 6px;">
                Admin Panel
            </p>
            <h1 class="adash_hero_title">Garden Dashboard</h1>
            <p class="adash_hero_sub">Full system overview — {{ now()->format('l, j F Y') }}</p>
        </div>

        @if($activeSeason)
            <div class="adash_season_card">
                <i class="ti ti-calendar-event" style="font-size:20px;color:var(--adash_green);"></i>
                <div>
                    <span class="adash_season_label">Active Season</span>
                    <span class="adash_season_name">{{ $activeSeason->name ?? 'Current Season' }}</span>
                </div>
            </div>
        @else
            <div class="adash_alert_row adash_alert_row--amber">
                <i class="ti ti-alert-circle"></i>
                No active season found — allocation cannot run.
            </div>
        @endif
    </div>


    {{-- ══════════════════════════════════════════════
         TOP STATS STRIP
    ══════════════════════════════════════════════ --}}
    <div class="adash_stats_strip">

        <div class="adash_strip_stat">
            <div class="adash_strip_stat_icon adash_strip_stat_icon--green">
                <i class="ti ti-map-2"></i>
            </div>
            <span class="adash_strip_stat_label">Total Plots</span>
            <span class="adash_strip_stat_value">{{ $plotStats['total'] }}</span>
            <span class="adash_strip_stat_sub">{{ $plotStats['available'] }} available · {{ $plotStats['rented'] }} rented</span>
        </div>

        <div class="adash_strip_stat">
            <div class="adash_strip_stat_icon adash_strip_stat_icon--amber">
                <i class="ti ti-shopping-bag"></i>
            </div>
            <span class="adash_strip_stat_label">Marketplace</span>
            <span class="adash_strip_stat_value">{{ $marketStats['active_listings'] }}</span>
            <span class="adash_strip_stat_sub">{{ $marketStats['pending_trades'] }} pending trades</span>
        </div>

        <div class="adash_strip_stat">
            <div class="adash_strip_stat_icon adash_strip_stat_icon--green">
                <i class="ti ti-seeding"></i>
            </div>
            <span class="adash_strip_stat_label">Seed Bank</span>
            <span class="adash_strip_stat_value">{{ $seedStats['total_seeds'] }}</span>
            <span class="adash_strip_stat_sub">{{ $seedStats['total_types'] }} types · {{ $seedStats['low_stock'] }} low stock</span>
        </div>

        <div class="adash_strip_stat">
            <div class="adash_strip_stat_icon adash_strip_stat_icon--sky">
                <i class="ti ti-tool"></i>
            </div>
            <span class="adash_strip_stat_label">Tools</span>
            <span class="adash_strip_stat_value">{{ $toolStats['total'] }}</span>
            <span class="adash_strip_stat_sub">{{ $toolStats['in_use'] }} in use · {{ $toolStats['maintenance_due'] }} maintenance</span>
        </div>

        <div class="adash_strip_stat">
            <div class="adash_strip_stat_icon adash_strip_stat_icon--purple">
                <i class="ti ti-heart-handshake"></i>
            </div>
            <span class="adash_strip_stat_label">Volunteer</span>
            <span class="adash_strip_stat_value">{{ $volunteerStats['open_shifts'] }}</span>
            <span class="adash_strip_stat_sub">{{ $volunteerStats['upcoming_shifts'] }} shifts this week</span>
        </div>

    </div>


    {{-- ══════════════════════════════════════════════
         BODY — TWO COLUMNS
    ══════════════════════════════════════════════ --}}
    <div class="adash_body">

        {{-- LEFT ────────────────────────────────────── --}}
        <div class="adash_left">

            {{-- PLOTS MODULE ────────────────────────── --}}
            <div class="adash_card">
                <div class="adash_card_header">
                    <div class="adash_card_header_left">
                        <div class="adash_card_icon adash_card_icon--green">
                            <i class="ti ti-map-2"></i>
                        </div>
                        <div>
                            <p class="adash_card_title">Plot & Rental</p>
                            <p class="adash_card_subtitle">Allotment management</p>
                        </div>
                    </div>
                    @if($plotStats['applications'] > 0)
                        <span class="adash_badge adash_badge--amber">
                            {{ $plotStats['applications'] }} pending applications
                        </span>
                    @endif
                </div>

                <div class="adash_card_body">

                    <div class="adash_mini_stats adash_mini_stats--4">
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Total</span>
                            <span class="adash_mini_stat_value">{{ $plotStats['total'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Available</span>
                            <span class="adash_mini_stat_value" style="color:var(--adash_green);">{{ $plotStats['available'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Rented</span>
                            <span class="adash_mini_stat_value">{{ $plotStats['rented'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Infected</span>
                            <span class="adash_mini_stat_value" style="{{ $plotStats['infected'] > 0 ? 'color:var(--adash_red)' : '' }}">
                                {{ $plotStats['infected'] }}
                            </span>
                        </div>
                    </div>

                    {{-- Occupancy bar --}}
                    <div class="adash_health_row">
                        <div class="adash_health_labels">
                            <span class="adash_health_title">Plot occupancy</span>
                            <span class="adash_health_pct">{{ $plotOccupancy }}%</span>
                        </div>
                        <div class="adash_health_bar">
                            <div class="adash_health_fill adash_health_fill--green" style="width:{{ $plotOccupancy }}%"></div>
                        </div>
                    </div>

                    @if($plotStats['infected'] > 0)
                        <div class="adash_alert_row adash_alert_row--red">
                            <i class="ti ti-virus"></i>
                            {{ $plotStats['infected'] }} plot(s) reporting active infections — check the plot registry.
                        </div>
                    @endif

                </div>

                <div class="adash_card_footer">
                    <a href="{{ url('/admin/plots') }}" class="adash_link">
                        Manage plots <i class="ti ti-arrow-right"></i>
                    </a>
                    @if($activeSeason)
                        <form method="POST" action="{{ route('rental.run') }}" style="display:inline;">
                            @csrf
                            <button type="submit" class="adash_action_btn">
                                <i class="ti ti-player-play"></i>
                                Run Allocation
                            </button>
                        </form>
                    @endif
                </div>
            </div>


            {{-- MARKETPLACE MODULE ──────────────────── --}}
            <div class="adash_card">
                <div class="adash_card_header">
                    <div class="adash_card_header_left">
                        <div class="adash_card_icon adash_card_icon--amber">
                            <i class="ti ti-shopping-bag"></i>
                        </div>
                        <div>
                            <p class="adash_card_title">Marketplace</p>
                            <p class="adash_card_subtitle">Listings, trades & canning</p>
                        </div>
                    </div>
                    @if($marketStats['flagged_allergens'] > 0)
                        <span class="adash_badge adash_badge--red">
                            <i class="ti ti-alert-triangle" style="font-size:10px;"></i>
                            {{ $marketStats['flagged_allergens'] }} allergen flags
                        </span>
                    @endif
                </div>

                <div class="adash_card_body">

                    <div class="adash_mini_stats">
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Active Listings</span>
                            <span class="adash_mini_stat_value">{{ $marketStats['active_listings'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Flash Live</span>
                            <span class="adash_mini_stat_value" style="color:var(--adash_amber);">{{ $marketStats['flash_live'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Pending Trades</span>
                            <span class="adash_mini_stat_value">{{ $marketStats['pending_trades'] }}</span>
                        </div>
                    </div>

                    @if($marketStats['flagged_allergens'] > 0)
                        <div class="adash_alert_row adash_alert_row--red">
                            <i class="ti ti-alert-triangle"></i>
                            {{ $marketStats['flagged_allergens'] }} active listing(s) with allergen flags require review.
                        </div>
                    @else
                        <div class="adash_alert_row adash_alert_row--green">
                            <i class="ti ti-circle-check"></i>
                            No allergen-flagged listings active. Market looks clean.
                        </div>
                    @endif

                </div>

                <div class="adash_card_footer">
                    <a href="{{ route('admin.marketplace.index') }}" class="adash_link">
                        Full marketplace overview <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>


            {{-- TOOLS + SEEDBANK ROW ─────────────────── --}}
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">

                {{-- Tools --}}
                <div class="adash_card">
                    <div class="adash_card_header">
                        <div class="adash_card_header_left">
                            <div class="adash_card_icon adash_card_icon--sky">
                                <i class="ti ti-tool"></i>
                            </div>
                            <div>
                                <p class="adash_card_title">Tools</p>
                                <p class="adash_card_subtitle">Library inventory</p>
                            </div>
                        </div>
                    </div>

                    <div class="adash_card_body">
                        <div class="adash_mini_stats adash_mini_stats--2">
                            <div class="adash_mini_stat">
                                <span class="adash_mini_stat_label">Available</span>
                                <span class="adash_mini_stat_value" style="color:var(--adash_green);">{{ $toolStats['available'] }}</span>
                            </div>
                            <div class="adash_mini_stat">
                                <span class="adash_mini_stat_label">In Use</span>
                                <span class="adash_mini_stat_value">{{ $toolStats['in_use'] }}</span>
                            </div>
                        </div>

                        <div class="adash_health_row">
                            <div class="adash_health_labels">
                                <span class="adash_health_title">Tool usage</span>
                                <span class="adash_health_pct">{{ $toolUsage }}%</span>
                            </div>
                            <div class="adash_health_bar">
                                <div class="adash_health_fill adash_health_fill--sky" style="width:{{ $toolUsage }}%"></div>
                            </div>
                        </div>

                        @if($toolStats['maintenance_due'] > 0)
                            <div class="adash_alert_row adash_alert_row--amber">
                                <i class="ti ti-settings"></i>
                                {{ $toolStats['maintenance_due'] }} tool(s) need maintenance.
                            </div>
                        @endif
                    </div>

                    <div class="adash_card_footer">
                        <a href="{{ route('admin.tools') }}" class="adash_link">
                            Manage tools <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>

                {{-- Seedbank --}}
                <div class="adash_card">
                    <div class="adash_card_header">
                        <div class="adash_card_header_left">
                            <div class="adash_card_icon adash_card_icon--green">
                                <i class="ti ti-seeding"></i>
                            </div>
                            <div>
                                <p class="adash_card_title">Seed Bank</p>
                                <p class="adash_card_subtitle">Vault inventory</p>
                            </div>
                        </div>
                        @if($seedStats['low_stock'] > 0)
                            <span class="adash_badge adash_badge--amber">{{ $seedStats['low_stock'] }} low</span>
                        @endif
                    </div>

                    <div class="adash_card_body">
                        <div class="adash_mini_stats adash_mini_stats--2">
                            <div class="adash_mini_stat">
                                <span class="adash_mini_stat_label">Total Seeds</span>
                                <span class="adash_mini_stat_value">{{ $seedStats['total_seeds'] }}</span>
                            </div>
                            <div class="adash_mini_stat">
                                <span class="adash_mini_stat_label">Types</span>
                                <span class="adash_mini_stat_value">{{ $seedStats['total_types'] }}</span>
                            </div>
                        </div>

                        @if($seedStats['low_stock'] > 0)
                            <div class="adash_alert_row adash_alert_row--amber">
                                <i class="ti ti-alert-circle"></i>
                                {{ $seedStats['low_stock'] }} seed type(s) running low (< 5 units).
                            </div>
                        @endif
                    </div>

                    <div class="adash_card_footer">
                        <a href="{{ url('/admin/seedbank') }}" class="adash_link">
                            Manage seeds <i class="ti ti-arrow-right"></i>
                        </a>
                    </div>
                </div>

            </div>

        </div>


        {{-- RIGHT SIDEBAR ────────────────────────────── --}}
        <div class="adash_right">

            {{-- RUN ALLOCATION ───────────────────────── --}}
            <div class="adash_allocation_card">
                <p style="font-size:10px;font-weight:600;letter-spacing:.08em;text-transform:uppercase;color:var(--adash_green);margin:0 0 8px;">
                    Seasonal Workflow
                </p>
                <h3 class="adash_allocation_title">Run Plot Allocation</h3>
                <p class="adash_allocation_desc">
                    Processes all pending rental applications against available plots for the
                    {{ $activeSeason?->name ?? 'current' }} season.
                    <strong>{{ $plotStats['applications'] }} applications</strong> in queue.
                </p>
                @if($activeSeason)
                    <form method="POST" action="{{ route('rental.run') }}">
                        @csrf
                        <button type="submit" class="adash_allocation_btn">
                            <i class="ti ti-player-play"></i>
                            Run Allocation Now
                        </button>
                    </form>
                @else
                    <div style="padding:10px 12px;background:rgba(0,0,0,.04);border-radius:var(--border-radius-md);font-size:12px;color:var(--color-text-tertiary);text-align:center;">
                        No active season — cannot run allocation.
                    </div>
                @endif
            </div>


            {{-- PENDING APPLICATIONS ─────────────────── --}}
            <div class="adash_card">
                <div class="adash_card_header">
                    <div>
                        <p class="adash_card_title">Applications</p>
                        <p class="adash_card_subtitle">Awaiting allocation</p>
                    </div>
                    <span class="adash_badge adash_badge--amber">{{ $plotStats['applications'] }}</span>
                </div>

                <div class="adash_card_body" style="padding:12px 16px;">
                    @forelse($recentApplications as $app)
                        <div class="adash_app_item">
                            <div>
                                <p class="adash_app_name">{{ $app->member_name }}</p>
                                <p class="adash_app_meta">
                                    Plot #{{ $app->plot_id }}
                                    · {{ \Carbon\Carbon::parse($app->created_at)->diffForHumans() }}
                                </p>
                            </div>
                            <span class="adash_badge adash_badge--amber">Pending</span>
                        </div>
                    @empty
                        <p style="font-size:13px;color:var(--color-text-secondary);text-align:center;padding:12px 0;margin:0;">
                            No pending applications.
                        </p>
                    @endforelse
                </div>

                <div class="adash_card_footer">
                    <a href="{{ url('/admin/plots') }}" class="adash_link">
                        See all applications <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>


            {{-- VOLUNTEER ────────────────────────────── --}}
            <div class="adash_card">
                <div class="adash_card_header">
                    <div class="adash_card_header_left">
                        <div class="adash_card_icon adash_card_icon--purple">
                            <i class="ti ti-heart-handshake"></i>
                        </div>
                        <div>
                            <p class="adash_card_title">Volunteer</p>
                            <p class="adash_card_subtitle">This week's shifts</p>
                        </div>
                    </div>
                </div>

                <div class="adash_card_body" style="padding:12px 16px;">
                    <div class="adash_mini_stats adash_mini_stats--2">
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">Open Shifts</span>
                            <span class="adash_mini_stat_value" style="color:var(--adash_purple);">{{ $volunteerStats['open_shifts'] }}</span>
                        </div>
                        <div class="adash_mini_stat">
                            <span class="adash_mini_stat_label">This Week</span>
                            <span class="adash_mini_stat_value">{{ $volunteerStats['upcoming_shifts'] }}</span>
                        </div>
                    </div>
                </div>

                <div class="adash_card_footer">
                    <a href="{{ route('admin.volunteer') }}" class="adash_link">
                        Manage volunteer <i class="ti ti-arrow-right"></i>
                    </a>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection