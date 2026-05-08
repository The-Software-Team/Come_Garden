@extends('layouts.app')

@section('title', 'Marketplace — Admin')

@push('styles')
    @vite(['resources/css/domain/marketplace.css'])
@endpush

@section('content')

{{-- Alerts --}}
@if(session('message'))
    <div class="market_alert market_alert--success">
        <i class="ti ti-circle-check"></i>
        <span>{{ session('message') }}</span>
    </div>
@endif

@if(session('error') || $errors->any())
    <div class="market_alert market_alert--warning">
        <i class="ti ti-alert-circle"></i>
        <span>{{ session('error') ?? $errors->first() }}</span>
    </div>
@endif


{{-- Page header --}}
<div class="market_header" style="margin-bottom:20px;">
    <div class="market_header_left">
        <p class="market_section_label" style="margin-bottom:4px;">Admin Panel</p>
        <h1 class="market_page_title">Marketplace Overview</h1>
        <p class="market_page_sub">Monitor listings, trades, questions, and allergen flags.</p>
    </div>
    @if($stats['top_karma_user'])
        <div style="display:flex;align-items:center;gap:8px;padding:10px 16px;background:var(--market_karma_bg);border:0.5px solid var(--market_karma_border);border-radius:var(--border-radius-md);">
            <i class="ti ti-award" style="font-size:18px;color:var(--market_karma_text);"></i>
            <div>
                <p style="font-size:10px;text-transform:uppercase;letter-spacing:.07em;color:var(--market_karma_text);font-weight:500;margin:0 0 2px;">Top Karma</p>
                <p style="font-size:13px;font-weight:600;color:var(--market_karma_text);margin:0;">
                    {{ $stats['top_karma_user']->name }}
                    <span style="font-weight:400;font-size:12px;">· {{ $stats['top_karma_user']->karma_points }} pts</span>
                </p>
            </div>
        </div>
    @endif
</div>


{{-- ═══════════════════════════════════════════
     STATS GRID
     ═══════════════════════════════════════════ --}}
<div class="madmin_stats">

    {{-- Listings --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--green">
            <i class="ti ti-package"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Total Listings</span>
            <span class="madmin_stat_value">{{ $stats['total_listings'] }}</span>
            <span class="madmin_stat_sub">{{ $stats['active_listings'] }} active</span>
        </div>
    </div>

    {{-- Flash --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--flash">
            <i class="ti ti-bolt"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Flash Trades</span>
            <span class="madmin_stat_value">{{ $stats['flash_active'] }}</span>
            <span class="madmin_stat_sub">live right now</span>
        </div>
    </div>

    {{-- Gifts --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--gift">
            <i class="ti ti-heart"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Gift Listings</span>
            <span class="madmin_stat_value">{{ $stats['gift_listings'] }}</span>
            <span class="madmin_stat_sub">free to claim</span>
        </div>
    </div>

    {{-- Trades --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--sky">
            <i class="ti ti-arrows-exchange"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Total Trades</span>
            <span class="madmin_stat_value">{{ $stats['total_trades'] }}</span>
            <span class="madmin_stat_sub">{{ $stats['pending_trades'] }} pending</span>
        </div>
    </div>

    {{-- Questions --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--purple">
            <i class="ti ti-help"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Questions</span>
            <span class="madmin_stat_value">{{ $stats['total_questions'] }}</span>
            <span class="madmin_stat_sub">{{ $stats['unanswered_questions'] }} unanswered</span>
        </div>
    </div>

    {{-- Canning --}}
    <div class="madmin_stat_card">
        <div class="madmin_stat_icon madmin_stat_icon--green">
            <i class="ti ti-tool"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Canning Sessions</span>
            <span class="madmin_stat_value">{{ $stats['canning_sessions'] }}</span>
            <span class="madmin_stat_sub">{{ $stats['open_sessions'] }} open</span>
        </div>
    </div>

    {{-- Allergen flags --}}
    <div class="madmin_stat_card madmin_stat_card--alert">
        <div class="madmin_stat_icon madmin_stat_icon--red">
            <i class="ti ti-alert-triangle"></i>
        </div>
        <div class="madmin_stat_body">
            <span class="madmin_stat_label">Allergen Flagged</span>
            <span class="madmin_stat_value" style="color:var(--market_red_text);">
                {{ $stats['flagged_allergens'] }}
            </span>
            <span class="madmin_stat_sub">active listings</span>
        </div>
    </div>

</div>


{{-- ═══════════════════════════════════════════
     BODY — TWO COLUMNS
     ═══════════════════════════════════════════ --}}
<div class="madmin_body">


    {{-- LEFT COLUMN ──────────────────────────── --}}
    <div class="madmin_left">

        {{-- RECENT LISTINGS ─────────────────── --}}
        <div class="madmin_card">

            <div class="madmin_card_header">
                <div>
                    <p class="market_section_label">Activity</p>
                    <h2 class="madmin_card_title">Recent Listings</h2>
                </div>
                <span class="market_badge market_badge--open">Last 5</span>
            </div>

            <div class="madmin_table_wrap">
                @if($recentListings->isNotEmpty())
                    <table class="madmin_table">
                        <thead>
                            <tr>
                                <th>Produce</th>
                                <th>Seller</th>
                                <th>Type</th>
                                <th>Qty</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Posted</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentListings as $listing)
                                <tr>
                                    <td>
                                        <p class="madmin_table_name">{{ $listing->produce_name }}</p>
                                        @if($listing->allergen_flags)
                                            <p class="madmin_table_sub" style="color:var(--market_red_text);">
                                                <i class="ti ti-alert-triangle" style="font-size:10px;"></i>
                                                {{ $listing->allergen_flags }}
                                            </p>
                                        @endif
                                    </td>
                                    <td style="font-size:13px;">{{ $listing->user->name ?? '—' }}</td>
                                    <td>
                                        <span class="market_badge market_badge--{{ $listing->type }}">
                                            {{ ucfirst($listing->type) }}
                                        </span>
                                    </td>
                                    <td style="font-size:13px;">{{ $listing->quantity_kg }}kg</td>
                                    <td style="font-size:13px;">
                                        {{ $listing->type === 'gift' ? 'Free' : '£' . number_format($listing->price ?? 0, 2) }}
                                    </td>
                                    <td>
                                        <span class="market_badge market_badge--{{ $listing->status }}">
                                            {{ ucfirst($listing->status) }}
                                        </span>
                                    </td>
                                    <td style="font-size:11px;color:var(--color-text-tertiary);">
                                        {{ $listing->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="madmin_table_empty">No listings yet.</div>
                @endif
            </div>

        </div>


        {{-- RECENT TRADES ────────────────────── --}}
        <div class="madmin_card">

            <div class="madmin_card_header">
                <div>
                    <p class="market_section_label">Exchanges</p>
                    <h2 class="madmin_card_title">Recent Trades</h2>
                </div>
                <span class="market_badge market_badge--open">Last 5</span>
            </div>

            <div class="madmin_table_wrap">
                @if($recentTrades->isNotEmpty())
                    <table class="madmin_table">
                        <thead>
                            <tr>
                                <th>Listing</th>
                                <th>Buyer</th>
                                <th>Seller</th>
                                <th>Status</th>
                                <th>When</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentTrades as $trade)
                                <tr>
                                    <td>
                                        <p class="madmin_table_name">
                                            {{ $trade->listing->produce_name ?? 'Unknown' }}
                                        </p>
                                        @if($trade->note)
                                            <p class="madmin_table_sub">{{ Str::limit($trade->note, 40) }}</p>
                                        @endif
                                    </td>
                                    <td style="font-size:13px;">{{ $trade->buyer->name ?? '—' }}</td>
                                    <td style="font-size:13px;">{{ $trade->seller->name ?? '—' }}</td>
                                    <td>
                                        <span class="market_badge market_badge--{{ $trade->status }}">
                                            {{ ucfirst($trade->status) }}
                                        </span>
                                    </td>
                                    <td style="font-size:11px;color:var(--color-text-tertiary);">
                                        {{ $trade->created_at->diffForHumans() }}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @else
                    <div class="madmin_table_empty">No trades yet.</div>
                @endif
            </div>

        </div>

    </div>


    {{-- RIGHT COLUMN ─────────────────────────── --}}
    <div class="madmin_right">

        {{-- ALLERGEN FLAGS ───────────────────── --}}
        <div class="madmin_card">

            <div class="madmin_card_header">
                <div>
                    <p class="market_section_label">Safety</p>
                    <h2 class="madmin_card_title">Allergen Flags</h2>
                </div>
                @if($stats['flagged_allergens'] > 0)
                    <span class="market_badge" style="background:var(--market_red_bg);color:var(--market_red_text);">
                        <i class="ti ti-alert-triangle" style="font-size:10px;"></i>
                        {{ $stats['flagged_allergens'] }} active
                    </span>
                @else
                    <span class="market_badge market_badge--available">All clear</span>
                @endif
            </div>

            <div style="padding:14px 18px;">
                @if($flaggedListings->isNotEmpty())
                    <div class="madmin_flag_list">
                        @foreach($flaggedListings as $listing)
                            <div class="madmin_flag_item">

                                <div class="madmin_flag_top">
                                    <div>
                                        <p class="madmin_flag_name">{{ $listing->produce_name }}</p>
                                        <p class="madmin_flag_meta">
                                            by {{ $listing->user->name ?? '—' }}
                                            · {{ $listing->created_at->diffForHumans() }}
                                        </p>
                                    </div>
                                    <span class="market_badge market_badge--{{ $listing->type }}">
                                        {{ ucfirst($listing->type) }}
                                    </span>
                                </div>

                                <div class="market_allergen_row" style="margin-top:8px;">
                                    @foreach(explode(',', $listing->allergen_flags) as $flag)
                                        <span class="market_allergen_badge">
                                            <i class="ti ti-alert-triangle"></i>
                                            {{ trim($flag) }}
                                        </span>
                                    @endforeach
                                </div>

                            </div>
                        @endforeach
                    </div>
                @else
                    <div style="padding:1.5rem 0;text-align:center;">
                        <i class="ti ti-circle-check" style="font-size:28px;color:var(--market_green);display:block;margin-bottom:8px;"></i>
                        <p style="font-size:13px;color:var(--color-text-secondary);margin:0;">
                            No allergen-flagged listings active.
                        </p>
                    </div>
                @endif
            </div>

        </div>


        {{-- QUICK HEALTH ─────────────────────── --}}
        <div class="madmin_card">

            <div class="madmin_card_header">
                <div>
                    <p class="market_section_label">At a Glance</p>
                    <h2 class="madmin_card_title">Market Health</h2>
                </div>
            </div>

            <div style="padding:14px 18px;display:flex;flex-direction:column;gap:10px;">

                {{-- Pending trades ratio --}}
                @php
                    $pendingPct = $stats['total_trades'] > 0
                        ? round(($stats['pending_trades'] / $stats['total_trades']) * 100)
                        : 0;
                    $unansweredPct = $stats['total_questions'] > 0
                        ? round(($stats['unanswered_questions'] / $stats['total_questions']) * 100)
                        : 0;
                    $canningPct = $stats['canning_sessions'] > 0
                        ? round(($stats['open_sessions'] / $stats['canning_sessions']) * 100)
                        : 0;
                @endphp

                <div class="madmin_health_row">
                    <div class="madmin_health_labels">
                        <span class="madmin_health_title">Trades resolved</span>
                        <span class="madmin_health_pct">{{ 100 - $pendingPct }}%</span>
                    </div>
                    <div class="madmin_health_bar">
                        <div class="madmin_health_fill madmin_health_fill--green"
                             style="width:{{ 100 - $pendingPct }}%"></div>
                    </div>
                </div>

                <div class="madmin_health_row">
                    <div class="madmin_health_labels">
                        <span class="madmin_health_title">Questions answered</span>
                        <span class="madmin_health_pct">{{ 100 - $unansweredPct }}%</span>
                    </div>
                    <div class="madmin_health_bar">
                        <div class="madmin_health_fill madmin_health_fill--purple"
                             style="width:{{ 100 - $unansweredPct }}%"></div>
                    </div>
                </div>

                <div class="madmin_health_row">
                    <div class="madmin_health_labels">
                        <span class="madmin_health_title">Canning sessions open</span>
                        <span class="madmin_health_pct">{{ $canningPct }}%</span>
                    </div>
                    <div class="madmin_health_bar">
                        <div class="madmin_health_fill madmin_health_fill--sky"
                             style="width:{{ $canningPct }}%"></div>
                    </div>
                </div>

                <div class="madmin_health_row">
                    <div class="madmin_health_labels">
                        <span class="madmin_health_title">Allergen exposure</span>
                        <span class="madmin_health_pct"
                              style="{{ $stats['flagged_allergens'] > 0 ? 'color:var(--market_red_text)' : '' }}">
                            {{ $stats['flagged_allergens'] }} listing(s)
                        </span>
                    </div>
                    <div class="madmin_health_bar">
                        @php
                            $allergenPct = $stats['active_listings'] > 0
                                ? round(($stats['flagged_allergens'] / $stats['active_listings']) * 100)
                                : 0;
                        @endphp
                        <div class="madmin_health_fill madmin_health_fill--red"
                             style="width:{{ $allergenPct }}%"></div>
                    </div>
                </div>

            </div>

        </div>

    </div>

</div>

@endsection