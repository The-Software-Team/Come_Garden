@extends('layouts.app')

@section('title', 'My Garden')

@push('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        /* ══════════════════════════════════════════════════════════
           MEMBER DASHBOARD  ·  Living Field Journal
           Prefix: gd_  (garden dashboard)
        ══════════════════════════════════════════════════════════ */

        :root {
            --gd_earth:        #2a1f14;
            --gd_bark:         #5c3d1e;
            --gd_moss:         #2a5c1e;
            --gd_moss_mid:     #3d7a2f;
            --gd_moss_light:   #5a9e48;
            --gd_moss_pale:    #e2eeda;
            --gd_moss_bg:      #f1f7ec;
            --gd_cream:        #f8f3eb;
            --gd_cream_dark:   #ede5d5;
            --gd_sky:          #1d6ea8;
            --gd_sky_bg:       #e8f2fb;
            --gd_amber:        #b45309;
            --gd_amber_bg:     #fef3c7;
            --gd_amber_pale:   #fffbeb;
            --gd_red:          #7b1f1f;
            --gd_red_bg:       #fceaea;
            --gd_purple:       #5b21b6;
            --gd_purple_bg:    #f5f3ff;
            --gd_border:       rgba(42, 92, 30, 0.14);
            --gd_shadow:       0 2px 16px rgba(42, 92, 30, 0.07);

            --gd_font_display: 'Playfair Display', Georgia, serif;
            --gd_font_ui:      'DM Sans', system-ui, sans-serif;
        }

        /* ── Base ── */
        .gd_wrap {
            font-family: var(--gd_font_ui);
            display: flex;
            flex-direction: column;
            gap: 22px;
        }

        /* ════════════════════════════════════════════
           HERO BANNER
        ════════════════════════════════════════════ */
        .gd_hero {
            background: var(--gd_moss);
            border-radius: var(--border-radius-lg, 12px);
            overflow: hidden;
            position: relative;
            min-height: 160px;
            display: flex;
            align-items: flex-end;
        }

        /* Radial glow */
        .gd_hero::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 360px; height: 360px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.09) 0%, transparent 65%);
            pointer-events: none;
        }

        /* Botanical SVG decoration — top right */
        .gd_hero_botanical {
            position: absolute;
            top: 0; right: 0;
            width: 320px; height: 160px;
            opacity: .18;
            pointer-events: none;
        }

        .gd_hero_content {
            position: relative;
            z-index: 2;
            padding: 26px 30px;
            display: flex;
            align-items: flex-end;
            justify-content: space-between;
            width: 100%;
            gap: 16px;
            flex-wrap: wrap;
        }

        .gd_hero_left {}

        .gd_hero_eyebrow {
            font-family: var(--gd_font_ui);
            font-size: 10px;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: rgba(255,255,255,.55);
            margin: 0 0 6px;
            display: flex;
            align-items: center;
            gap: 6px;
        }
        .gd_hero_eyebrow::before {
            content: '';
            display: block;
            width: 16px; height: 1px;
            background: rgba(255,255,255,.4);
        }

        .gd_hero_greeting {
            font-family: var(--gd_font_display);
            font-size: clamp(22px, 3vw, 30px);
            font-weight: 600;
            color: #fff;
            margin: 0 0 6px;
            letter-spacing: -.01em;
            line-height: 1.1;
        }
        .gd_hero_greeting em {
            font-style: italic;
            color: #a8d98e;
        }

        .gd_hero_sub {
            font-size: 13px;
            color: rgba(255,255,255,.6);
            margin: 0;
        }

        /* Right side — date + season chips */
        .gd_hero_right {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            gap: 8px;
        }
        .gd_hero_date {
            font-family: var(--gd_font_display);
            font-size: 13px;
            font-style: italic;
            color: rgba(255,255,255,.7);
        }
        .gd_hero_chips {
            display: flex;
            gap: 7px;
            flex-wrap: wrap;
            justify-content: flex-end;
        }
        .gd_chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 4px 11px;
            border-radius: 999px;
            font-size: 11px;
            font-weight: 500;
            border: 1px solid;
        }
        .gd_chip--light  { background: rgba(255,255,255,.12); color: rgba(255,255,255,.85); border-color: rgba(255,255,255,.18); }
        .gd_chip--cream  { background: rgba(245,240,232,.15); color: #e8d9c0; border-color: rgba(245,240,232,.25); }
        .gd_chip--alert  { background: rgba(123,31,31,.35); color: #f4b8b8; border-color: rgba(240,100,100,.3); }
        .gd_chip i { font-size: 11px; }

        /* ════════════════════════════════════════════
           MAIN GRID
        ════════════════════════════════════════════ */
        .gd_grid {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            grid-template-rows: auto auto;
            gap: 14px;
        }

        /* ── Generic card shell ── */
        .gd_card {
            background: var(--color-background-primary, #fff);
            border: 0.5px solid var(--color-border-tertiary, #e5e7eb);
            border-radius: var(--border-radius-lg, 12px);
            overflow: hidden;
            display: flex;
            flex-direction: column;
            transition: border-color .2s, box-shadow .2s;
            animation: gd_fade_in .4s ease backwards;
        }
        .gd_card:hover {
            border-color: var(--gd_border);
            box-shadow: var(--gd_shadow);
        }

        .gd_card--plot {
            grid-column: span 2;
            border-color: var(--gd_border);
        }
        .gd_card--infected {
            border-color: rgba(123,31,31,.28);
        }

        .gd_card_head {
            padding: 14px 18px 12px;
            border-bottom: 0.5px solid var(--color-border-tertiary, #e5e7eb);
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 10px;
        }
        .gd_card_head_left {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .gd_icon_wrap {
            width: 34px; height: 34px;
            border-radius: 9px;
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .gd_icon_wrap i { font-size: 16px; }
        .gd_icon_wrap--moss   { background: var(--gd_moss_pale); color: var(--gd_moss); }
        .gd_icon_wrap--sky    { background: var(--gd_sky_bg);    color: var(--gd_sky); }
        .gd_icon_wrap--amber  { background: var(--gd_amber_bg);  color: var(--gd_amber); }
        .gd_icon_wrap--purple { background: var(--gd_purple_bg); color: var(--gd_purple); }
        .gd_icon_wrap--red    { background: var(--gd_red_bg);    color: var(--gd_red); }
        .gd_icon_wrap--cream  { background: var(--gd_cream_dark); color: var(--gd_bark); }

        .gd_card_title    { font-size: 13px; font-weight: 600; color: var(--color-text-primary); margin: 0; }
        .gd_card_subtitle { font-size: 11px; color: var(--color-text-tertiary); margin: 0; }

        .gd_card_body {
            padding: 16px 18px;
            flex: 1;
            display: flex;
            flex-direction: column;
            gap: 12px;
        }

        .gd_card_foot {
            padding: 10px 18px;
            border-top: 0.5px solid var(--color-border-tertiary, #e5e7eb);
            background: var(--color-background-secondary, #f9fafb);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .gd_link {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            font-size: 12px;
            font-weight: 500;
            color: var(--gd_moss_mid);
            text-decoration: none;
            transition: gap .15s;
        }
        .gd_link:hover { gap: 8px; }
        .gd_link i { font-size: 13px; }

        /* ── Badges ── */
        .gd_badge {
            padding: 3px 9px;
            border-radius: 999px;
            font-size: 10px;
            font-weight: 500;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .gd_badge--moss   { background: var(--gd_moss_pale); color: #24521a; }
        .gd_badge--amber  { background: var(--gd_amber_bg);  color: var(--gd_amber); }
        .gd_badge--red    { background: var(--gd_red_bg);    color: var(--gd_red); }
        .gd_badge--sky    { background: var(--gd_sky_bg);    color: var(--gd_sky); }
        .gd_badge--purple { background: var(--gd_purple_bg); color: var(--gd_purple); }
        .gd_badge--rented { background: var(--gd_sky_bg);    color: var(--gd_sky); }

        /* ════════════════════════════════════════════
           PLOT MODULE — centrepiece
        ════════════════════════════════════════════ */

        /* Two-column layout inside plot card body */
        .gd_plot_body {
            display: grid;
            grid-template-columns: 1fr 180px;
            gap: 16px;
        }
        .gd_plot_left  { display: flex; flex-direction: column; gap: 12px; }
        .gd_plot_right { display: flex; flex-direction: column; gap: 12px; align-items: center; }

        /* Stat row */
        .gd_stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
        }
        .gd_stat {
            background: var(--gd_moss_bg);
            border: 0.5px solid var(--gd_border);
            border-radius: 8px;
            padding: 10px 12px;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .gd_stat_lbl {
            font-size: 9px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--gd_moss_mid);
            font-weight: 500;
        }
        .gd_stat_val {
            font-size: 18px;
            font-weight: 600;
            color: var(--color-text-primary);
            line-height: 1;
            font-family: var(--gd_font_display);
        }
        .gd_stat_sub {
            font-size: 10px;
            color: var(--color-text-tertiary);
        }

        /* Soil ring row */
        .gd_soil_row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 12px 14px;
            background: var(--gd_cream);
            border: 0.5px solid var(--gd_cream_dark);
            border-radius: 10px;
        }
        .gd_soil_info { flex: 1; min-width: 0; }
        .gd_soil_title {
            font-family: var(--gd_font_display);
            font-size: 14px;
            font-weight: 600;
            color: var(--color-text-primary);
            margin: 0 0 3px;
            text-transform: capitalize;
        }
        .gd_soil_desc {
            font-size: 11px;
            color: var(--color-text-secondary);
            margin: 0;
            line-height: 1.45;
        }

        /* Infection alert strip */
        .gd_infection_strip {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 12px;
            background: var(--gd_red_bg);
            border: 0.5px solid rgba(123,31,31,.22);
            border-radius: 8px;
            font-size: 12px;
            color: var(--gd_red);
            line-height: 1.4;
        }
        .gd_infection_strip i { font-size: 15px; flex-shrink: 0; }

        /* Plot footprint SVG card */
        .gd_footprint_card {
            background: var(--gd_moss_bg);
            border: 0.5px solid var(--gd_border);
            border-radius: 10px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 6px;
        }
        .gd_footprint_lbl {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gd_moss_mid);
        }

        /* Sun arc card */
        .gd_sun_card {
            background: var(--gd_amber_pale);
            border: 0.5px solid rgba(180,83,9,.14);
            border-radius: 10px;
            padding: 12px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .gd_sun_lbl {
            font-size: 9px;
            font-weight: 600;
            letter-spacing: .08em;
            text-transform: uppercase;
            color: var(--gd_amber);
        }
        .gd_sun_profile_name {
            font-family: var(--gd_font_display);
            font-size: 13px;
            font-weight: 600;
            color: var(--gd_amber);
            margin-top: 2px;
        }
        .gd_sun_desc {
            font-size: 10px;
            color: var(--gd_amber);
            opacity: .7;
            text-align: center;
        }

        /* No-plot state */
        .gd_no_plot {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 12px;
            padding: 28px;
            text-align: center;
            flex: 1;
        }
        .gd_no_plot_ring {
            width: 64px; height: 64px;
            border-radius: 50%;
            background: var(--gd_moss_pale);
            display: flex; align-items: center; justify-content: center;
            border: 2px dashed rgba(42,92,30,.25);
        }
        .gd_no_plot_ring i { font-size: 26px; color: var(--gd_moss); }
        .gd_no_plot h3 {
            font-family: var(--gd_font_display);
            font-size: 16px; font-weight: 600; margin: 0;
        }
        .gd_no_plot p {
            font-size: 13px;
            color: var(--color-text-secondary);
            margin: 0; max-width: 220px; line-height: 1.55;
        }
        .gd_no_plot_btn {
            height: 40px; padding: 0 22px;
            border: none; border-radius: 8px;
            background: var(--gd_moss);
            color: #fff;
            font-family: var(--gd_font_ui);
            font-size: 13px; font-weight: 500;
            display: inline-flex; align-items: center; gap: 7px;
            text-decoration: none;
            transition: background .15s;
        }
        .gd_no_plot_btn:hover { background: var(--gd_moss_mid); }

        /* ════════════════════════════════════════════
           SEED BANK MODULE
        ════════════════════════════════════════════ */
        .gd_seeds_visual {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 0 4px;
        }
        .gd_seed_pod {
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 4px;
        }
        .gd_seed_pod_dot {
            border-radius: 50%;
            background: var(--gd_moss_pale);
            border: 1.5px solid var(--gd_border);
            display: flex; align-items: center; justify-content: center;
        }
        .gd_seed_pod_dot--lg { width: 36px; height: 36px; }
        .gd_seed_pod_dot--md { width: 28px; height: 28px; }
        .gd_seed_pod_dot--sm { width: 22px; height: 22px; }
        .gd_seed_pod_dot i   { font-size: 14px; color: var(--gd_moss); }
        .gd_seed_pod_dot--md i { font-size: 11px; }
        .gd_seed_pod_dot--sm i { font-size: 9px; }
        .gd_seed_pod_lbl {
            font-size: 9px;
            color: var(--color-text-tertiary);
            text-align: center;
        }

        .gd_credit_row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 10px 12px;
            background: var(--gd_moss_bg);
            border: 0.5px solid var(--gd_border);
            border-radius: 8px;
        }
        .gd_credit_lbl { font-size: 12px; color: var(--color-text-secondary); }
        .gd_credit_val {
            font-family: var(--gd_font_display);
            font-size: 20px; font-weight: 700;
            color: var(--gd_moss);
        }

        /* ════════════════════════════════════════════
           MARKETPLACE MODULE
        ════════════════════════════════════════════ */
        .gd_market_pair {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 8px;
        }
        .gd_market_stat {
            background: var(--color-background-secondary);
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 8px;
            padding: 12px;
            text-align: center;
        }
        .gd_market_stat_val {
            font-family: var(--gd_font_display);
            font-size: 26px; font-weight: 700;
            color: var(--color-text-primary);
            line-height: 1;
            display: block;
        }
        .gd_market_stat_lbl {
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .06em;
            color: var(--color-text-tertiary);
            margin-top: 4px;
            display: block;
        }
        .gd_trade_alert {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 8px 11px;
            background: var(--gd_amber_bg);
            border: 0.5px solid rgba(180,83,9,.2);
            border-radius: 8px;
            font-size: 12px;
            color: var(--gd_amber);
        }
        .gd_trade_alert i { font-size: 14px; flex-shrink: 0; }

        /* ════════════════════════════════════════════
           TOOL LIBRARY MODULE
        ════════════════════════════════════════════ */
        .gd_tools_row {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 10px 12px;
            background: var(--gd_sky_bg);
            border: 0.5px solid rgba(29,110,168,.14);
            border-radius: 8px;
        }
        .gd_tools_icon {
            width: 36px; height: 36px;
            border-radius: 8px;
            background: rgba(29,110,168,.12);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .gd_tools_icon i { font-size: 17px; color: var(--gd_sky); }
        .gd_tools_val {
            font-family: var(--gd_font_display);
            font-size: 22px; font-weight: 700;
            color: var(--gd_sky);
        }
        .gd_tools_lbl { font-size: 11px; color: var(--gd_sky); opacity: .7; }

        /* ════════════════════════════════════════════
           VOLUNTEER MODULE
        ════════════════════════════════════════════ */
        .gd_shift_item {
            display: flex;
            align-items: center;
            gap: 10px;
            padding: 9px 10px;
            border: 0.5px solid var(--color-border-tertiary);
            border-radius: 8px;
            background: var(--color-background-secondary);
            transition: background .12s;
        }
        .gd_shift_item:hover { background: var(--gd_purple_bg); }

        .gd_shift_cal {
            width: 38px; height: 38px;
            border-radius: 8px;
            background: var(--gd_purple_bg);
            border: 0.5px solid rgba(91,33,182,.15);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }
        .gd_shift_day   { font-size: 14px; font-weight: 700; color: var(--gd_purple); line-height: 1; }
        .gd_shift_month { font-size: 9px;  font-weight: 500; color: var(--gd_purple); text-transform: uppercase; }
        .gd_shift_title { font-size: 12px; font-weight: 500; color: var(--color-text-primary); margin: 0; }
        .gd_shift_time  { font-size: 11px; color: var(--color-text-tertiary); margin: 0; }

        /* ════════════════════════════════════════════
           EMPTY STATES
        ════════════════════════════════════════════ */
        .gd_empty {
            font-size: 12px;
            color: var(--color-text-tertiary);
            text-align: center;
            padding: 8px 0;
            font-style: italic;
        }

        /* ════════════════════════════════════════════
           ANIMATIONS
        ════════════════════════════════════════════ */
        @keyframes gd_fade_in {
            from { opacity: 0; transform: translateY(8px); }
            to   { opacity: 1; transform: none; }
        }
        .gd_card:nth-child(1) { animation-delay: .03s; }
        .gd_card:nth-child(2) { animation-delay: .08s; }
        .gd_card:nth-child(3) { animation-delay: .13s; }
        .gd_card:nth-child(4) { animation-delay: .18s; }
        .gd_card:nth-child(5) { animation-delay: .23s; }

        /* ════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .gd_grid        { grid-template-columns: 1fr 1fr; }
            .gd_card--plot  { grid-column: span 2; }
            .gd_stats       { grid-template-columns: repeat(2, 1fr); }
        }
        @media (max-width: 720px) {
            .gd_grid        { grid-template-columns: 1fr; }
            .gd_card--plot  { grid-column: span 1; }
            .gd_plot_body   { grid-template-columns: 1fr; }
            .gd_plot_right  { flex-direction: row; justify-content: center; flex-wrap: wrap; }
            .gd_hero_right  { display: none; }
        }
    </style>
@endpush

@section('content')

@php
    /* ── Soil state ── */
    $soilState = $plot?->soil_quality ?? 'neutral';
    $soilMeta  = match($soilState) {
        'healthy'    => ['icon' => 'ti-heart',        'color' => '#3d7a2f', 'pct' => 85,
                         'desc' => 'Good crop diversity — soil is thriving.'],
        'recovering' => ['icon' => 'ti-refresh',      'color' => '#1d6ea8', 'pct' => 60,
                         'desc' => 'Organic fertiliser applied — recovering steadily.'],
        'depleted'   => ['icon' => 'ti-alert-circle', 'color' => '#b45309', 'pct' => 20,
                         'desc' => 'Same crop repeated — consider rotation soon.'],
        default      => ['icon' => 'ti-minus',        'color' => '#6b7280', 'pct' => 40,
                         'desc' => 'Plant more crops or add fertiliser to improve.'],
    };
    $soilBadge = match($soilState) {
        'healthy' => 'moss', 'recovering' => 'sky', 'depleted' => 'amber', default => 'purple'
    };

    /* ── SVG ring math ── */
    $r = 26; $cx = 32; $cy = 32;
    $circ = 2 * M_PI * $r;
    $dash = round(($soilMeta['pct'] / 100) * $circ, 2);

    /* ── Zone / sun ── */
    $sunProfile = $plot?->sun_profile ?? 'center';
    $zoneLabel  = match($sunProfile) { 'west' => 'West Wing', 'east' => 'East Wing', default => 'Central' };
    $sunLabel   = match($sunProfile) { 'west' => 'Afternoon Sun', 'east' => 'Morning Sun', default => 'Full Sun' };
    $sunArcRot  = match($sunProfile) { 'west' => 135, 'east' => 45, default => 90 };

    /* ── Plot footprint scale ── */
    $pw = $plot?->width  ?? 10;
    $ph = $plot?->height ?? 10;
    $fpAspect = $pw / $ph;
    $fpMaxW = 100; $fpMaxH = 110;
    if ($fpAspect > $fpMaxW / $fpMaxH) { $fpW = $fpMaxW; $fpH = $fpMaxW / $fpAspect; }
    else { $fpH = $fpMaxH; $fpW = $fpMaxH * $fpAspect; }
    $fpX = (120 - $fpW) / 2; $fpY = (130 - $fpH) / 2;

    /* ── Greeting ── */
    $hour = now()->hour;
    $greeting = $hour < 12 ? 'Good morning' : ($hour < 18 ? 'Good afternoon' : 'Good evening');
    $firstName = explode(' ', $member->name)[0];
@endphp

<div class="gd_wrap">

    {{-- ════════════════════════════════════════════
         HERO BANNER
    ════════════════════════════════════════════ --}}
    <div class="gd_hero">

        {{-- Botanical SVG decoration --}}
        <svg class="gd_hero_botanical" viewBox="0 0 320 160" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
            {{-- Large leaf cluster --}}
            <ellipse cx="240" cy="30" rx="55" ry="22" fill="white" transform="rotate(-35 240 30)"/>
            <ellipse cx="270" cy="55" rx="48" ry="18" fill="white" transform="rotate(-20 270 55)"/>
            <ellipse cx="210" cy="60" rx="42" ry="16" fill="white" transform="rotate(-50 210 60)"/>
            <ellipse cx="290" cy="25" rx="36" ry="14" fill="white" transform="rotate(-10 290 25)"/>
            {{-- Stems --}}
            <path d="M255 140 Q250 90 240 30" stroke="white" stroke-width="2" fill="none"/>
            <path d="M255 140 Q260 80 270 55" stroke="white" stroke-width="2" fill="none"/>
            <path d="M255 140 Q240 100 210 60" stroke="white" stroke-width="2" fill="none"/>
            {{-- Small berries --}}
            <circle cx="300" cy="80" r="5" fill="white"/>
            <circle cx="312" cy="68" r="4" fill="white"/>
            <circle cx="307" cy="92" r="3" fill="white"/>
            {{-- Ground line --}}
            <path d="M160 140 Q200 130 255 140 Q290 148 320 140" stroke="white" stroke-width="1.5" fill="none"/>
        </svg>

        <div class="gd_hero_content">
            <div class="gd_hero_left">
                <p class="gd_hero_eyebrow">
                    <i class="ti ti-plant"></i>
                    Come-Garden
                </p>
                <h1 class="gd_hero_greeting">
                    {{ $greeting }},<br><em>{{ $firstName }}</em>
                </h1>
                <p class="gd_hero_sub">
                    @if($plot)
                        Plot #{{ $plot->id }} · {{ $zoneLabel }} · {{ $sunLabel }}
                    @else
                        Welcome to your garden dashboard.
                    @endif
                </p>
            </div>

            <div class="gd_hero_right">
                <span class="gd_hero_date">{{ now()->format('l, j F Y') }}</span>
                <div class="gd_hero_chips">
                    @if($plot)
                        <span class="gd_chip gd_chip--light">
                            <i class="ti ti-map-2"></i>
                            Plot #{{ $plot->id }}
                        </span>
                        <span class="gd_chip gd_chip--cream">
                            <i class="ti ti-{{ $sunProfile === 'east' ? 'sunrise' : ($sunProfile === 'west' ? 'sunset' : 'sun') }}"></i>
                            {{ $sunLabel }}
                        </span>
                        @if($plot->infections->isNotEmpty())
                            <span class="gd_chip gd_chip--alert">
                                <i class="ti ti-alert-triangle"></i>
                                {{ $plot->infections->count() }} infection(s)
                            </span>
                        @else
                            <span class="gd_chip gd_chip--light">
                                <i class="ti ti-circle-check"></i>
                                Plot healthy
                            </span>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>


    {{-- ════════════════════════════════════════════
         MODULE GRID
    ════════════════════════════════════════════ --}}
    <div class="gd_grid">

        {{-- ══ MY PLOT ═══════════════════════════════ --}}
        <div class="gd_card gd_card--plot {{ $plot && $plot->infections->isNotEmpty() ? 'gd_card--infected' : '' }}">

            <div class="gd_card_head">
                <div class="gd_card_head_left">
                    <div class="gd_icon_wrap gd_icon_wrap--moss">
                        <i class="ti ti-map-2"></i>
                    </div>
                    <div>
                        <p class="gd_card_title">My Allotment</p>
                        <p class="gd_card_subtitle">
                            {{ $plot ? 'Plot #' . $plot->id . ' · ' . $zoneLabel : 'No active rental' }}
                        </p>
                    </div>
                </div>
                @if($plot)
                    <span class="gd_badge gd_badge--{{ $plot->status === 'rented' ? 'rented' : 'amber' }}">
                        {{ ucfirst($plot->status) }}
                    </span>
                @endif
            </div>

            <div class="gd_card_body">

                @if($plot)
                    <div class="gd_plot_body">

                        {{-- Left: stats + soil + infection --}}
                        <div class="gd_plot_left">

                            {{-- Four key stats --}}
                            <div class="gd_stats">
                                <div class="gd_stat">
                                    <span class="gd_stat_lbl">Area</span>
                                    <span class="gd_stat_val">{{ $plot->area ?? ($plot->width * $plot->height) }}</span>
                                    <span class="gd_stat_sub">m²</span>
                                </div>
                                <div class="gd_stat">
                                    <span class="gd_stat_lbl">Crops</span>
                                    <span class="gd_stat_val">{{ $plot->crops->count() }}</span>
                                    <span class="gd_stat_sub">planted</span>
                                </div>
                                <div class="gd_stat">
                                    <span class="gd_stat_lbl">Share</span>
                                    <span class="gd_stat_val">{{ round($activeParticipant->share * 100) }}%</span>
                                    <span class="gd_stat_sub">of plot</span>
                                </div>
                                <div class="gd_stat">
                                    <span class="gd_stat_lbl">Rent</span>
                                    <span class="gd_stat_val">£{{ number_format($activeParticipant->cost, 0) }}</span>
                                    <span class="gd_stat_sub">per period</span>
                                </div>
                            </div>

                            {{-- Soil health ring row --}}
                            <div class="gd_soil_row">
                                {{-- SVG ring --}}
                                <svg width="64" height="64" viewBox="0 0 64 64" style="flex-shrink:0;">
                                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                            fill="none" stroke="var(--gd_cream_dark)" stroke-width="5"/>
                                    <circle cx="{{ $cx }}" cy="{{ $cy }}" r="{{ $r }}"
                                            fill="none"
                                            stroke="{{ $soilMeta['color'] }}"
                                            stroke-width="5"
                                            stroke-linecap="round"
                                            stroke-dasharray="{{ $dash }} {{ round($circ, 2) }}"
                                            transform="rotate(-90, {{ $cx }}, {{ $cy }})"/>
                                    <text x="{{ $cx }}" y="{{ $cy + 5 }}"
                                          text-anchor="middle"
                                          font-size="11" font-weight="700"
                                          fill="{{ $soilMeta['color'] }}"
                                          font-family="DM Sans, sans-serif">{{ $soilMeta['pct'] }}%</text>
                                </svg>

                                <div class="gd_soil_info">
                                    <p class="gd_soil_title">Soil: {{ ucfirst($soilState) }}</p>
                                    <p class="gd_soil_desc">{{ $soilMeta['desc'] }}</p>
                                </div>

                                <span class="gd_badge gd_badge--{{ $soilBadge }}" style="align-self:flex-start;">
                                    <i class="ti {{ $soilMeta['icon'] }}" style="font-size:10px;margin-right:2px;"></i>
                                    {{ ucfirst($soilState) }}
                                </span>
                            </div>

                            {{-- Infection alert --}}
                            @if($plot->infections->isNotEmpty())
                                <div class="gd_infection_strip">
                                    <i class="ti ti-alert-triangle"></i>
                                    <span>
                                        <strong>{{ $plot->infections->count() }} active infection(s)</strong>
                                        — {{ $plot->infections->first()->type }}.
                                        Neighbouring plots have been alerted.
                                    </span>
                                </div>
                            @endif

                        </div>

                        {{-- Right: footprint + sun arc --}}
                        <div class="gd_plot_right">

                            {{-- Footprint --}}
                            <div class="gd_footprint_card" style="width:100%;">
                                <span class="gd_footprint_lbl">Plot Footprint</span>
                                <svg width="120" height="130" viewBox="0 0 120 130">
                                    {{-- Ground grid lines --}}
                                    @for($gi = 1; $gi < 4; $gi++)
                                        <line x1="{{ $fpX }}" y1="{{ $fpY + ($fpH / 4) * $gi }}"
                                              x2="{{ $fpX + $fpW }}" y2="{{ $fpY + ($fpH / 4) * $gi }}"
                                              stroke="rgba(42,92,30,.12)" stroke-width="0.7"/>
                                    @endfor
                                    @for($gi = 1; $gi < 3; $gi++)
                                        <line x1="{{ $fpX + ($fpW / 3) * $gi }}" y1="{{ $fpY }}"
                                              x2="{{ $fpX + ($fpW / 3) * $gi }}" y2="{{ $fpY + $fpH }}"
                                              stroke="rgba(42,92,30,.12)" stroke-width="0.7"/>
                                    @endfor
                                    {{-- Plot rect --}}
                                    <rect x="{{ $fpX }}" y="{{ $fpY }}"
                                          width="{{ $fpW }}" height="{{ $fpH }}"
                                          rx="3"
                                          fill="rgba(226,238,218,.6)"
                                          stroke="#3d7a2f"
                                          stroke-width="1.5"/>
                                    {{-- Dimension labels --}}
                                    <text x="60" y="{{ $fpY + $fpH + 14 }}"
                                          text-anchor="middle" font-size="9"
                                          fill="#6b7280" font-family="DM Sans, sans-serif">{{ $pw }} m</text>
                                    <text x="{{ $fpX - 6 }}" y="{{ $fpY + $fpH / 2 }}"
                                          text-anchor="middle" font-size="9"
                                          fill="#6b7280" font-family="DM Sans, sans-serif"
                                          transform="rotate(-90, {{ $fpX - 6 }}, {{ $fpY + $fpH / 2 }})">{{ $ph }} m</text>
                                </svg>
                            </div>

                            {{-- Sun profile card --}}
                            <div class="gd_sun_card" style="width:100%;">
                                <span class="gd_sun_lbl">Sun Profile</span>
                                {{-- Mini sun arc SVG --}}
                                <svg width="80" height="44" viewBox="0 0 80 44">
                                    {{-- Horizon --}}
                                    <line x1="4" y1="40" x2="76" y2="40" stroke="rgba(180,83,9,.25)" stroke-width="1"/>
                                    {{-- Arc --}}
                                    <path d="M 8 40 A 32 32 0 0 1 72 40"
                                          fill="none"
                                          stroke="rgba(180,83,9,.2)"
                                          stroke-width="2"
                                          stroke-dasharray="3 4"/>
                                    {{-- Sun dot --}}
                                    @php
                                        $sunAngle = $sunArcRot; // degrees: 45=east, 90=center, 135=west
                                        $sunRad = deg2rad($sunAngle);
                                        $sunX = round(40 - 32 * cos($sunRad), 2);
                                        $sunY = round(40 - 32 * sin($sunRad), 2);
                                    @endphp
                                    <circle cx="{{ $sunX }}" cy="{{ $sunY }}" r="5"
                                            fill="#b45309" opacity=".85"/>
                                    <circle cx="{{ $sunX }}" cy="{{ $sunY }}" r="9"
                                            fill="#b45309" opacity=".15"/>
                                    {{-- Labels --}}
                                    <text x="4"  y="40" text-anchor="start"  font-size="8" fill="rgba(180,83,9,.5)" dy="-3" font-family="DM Sans,sans-serif">W</text>
                                    <text x="76" y="40" text-anchor="end"    font-size="8" fill="rgba(180,83,9,.5)" dy="-3" font-family="DM Sans,sans-serif">E</text>
                                </svg>
                                <span class="gd_sun_profile_name">{{ $sunLabel }}</span>
                                <span class="gd_sun_desc">{{ $zoneLabel }}</span>
                            </div>

                        </div>
                    </div>

                @else
                    {{-- No plot --}}
                    <div class="gd_no_plot">
                        <div class="gd_no_plot_ring">
                            <i class="ti ti-map-pin-off"></i>
                        </div>
                        <h3>No Active Plot</h3>
                        <p>You don't have an allotment yet. Browse available plots and apply for a rental.</p>
                        <a href="{{ url('/plots') }}" class="gd_no_plot_btn">
                            <i class="ti ti-search"></i>
                            Browse Plots
                        </a>
                    </div>
                @endif

            </div>

            <div class="gd_card_foot">
                @if($plot)
                    <a href="{{ route('plots.show', $plot) }}" class="gd_link">
                        Manage my plot <i class="ti ti-arrow-right"></i>
                    </a>
                    <span style="font-size:11px;color:var(--color-text-tertiary);">
                        {{ $plot->width }}×{{ $plot->height }} m · {{ ucfirst($plot->size) }}
                    </span>
                @else
                    <a href="{{ url('/plots') }}" class="gd_link">
                        See available plots <i class="ti ti-arrow-right"></i>
                    </a>
                @endif
            </div>
        </div>


        {{-- ══ SEED BANK ══════════════════════════════ --}}
        <div class="gd_card">
            <div class="gd_card_head">
                <div class="gd_card_head_left">
                    <div class="gd_icon_wrap gd_icon_wrap--moss">
                        <i class="ti ti-seeding"></i>
                    </div>
                    <div>
                        <p class="gd_card_title">Seed Bank</p>
                        <p class="gd_card_subtitle">Your community vault</p>
                    </div>
                </div>
                @if(($seedbankProfile?->credits ?? 0) > 0)
                    <span class="gd_badge gd_badge--moss">{{ $seedbankProfile->credits }} credits</span>
                @endif
            </div>

            <div class="gd_card_body">
                {{-- Seed pod visual --}}
                <div class="gd_seeds_visual">
                    @php $seedCount_display = min($seedCount, 5); @endphp
                    @for($i = 0; $i < 3; $i++)
                        @php $sz = $i === 1 ? 'lg' : ($i === 0 ? 'md' : 'sm'); @endphp
                        <div class="gd_seed_pod">
                            <div class="gd_seed_pod_dot gd_seed_pod_dot--{{ $sz }}">
                                <i class="ti ti-seeding"></i>
                            </div>
                            @if($i < $seedCount_display)
                                <span class="gd_seed_pod_lbl" style="color:var(--gd_moss_mid);">●</span>
                            @else
                                <span class="gd_seed_pod_lbl">○</span>
                            @endif
                        </div>
                    @endfor
                </div>

                <div class="gd_credit_row">
                    <span class="gd_credit_lbl">Credits available</span>
                    <span class="gd_credit_val">{{ $seedbankProfile?->credits ?? 0 }}</span>
                </div>

                <div style="display:flex;align-items:center;justify-content:space-between;padding:6px 2px;">
                    <span style="font-size:12px;color:var(--color-text-secondary);">Seed types in vault</span>
                    <span style="font-family:var(--gd_font_display);font-size:18px;font-weight:700;color:var(--gd_moss);">{{ $seedCount }}</span>
                </div>

                @if(!$seedbankProfile)
                    <p class="gd_empty">No seed bank profile yet.</p>
                @endif
            </div>

            <div class="gd_card_foot">
                <a href="{{ route('seedbank.profile') }}" class="gd_link">
                    Go to Seed Bank <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>


        {{-- ══ MARKETPLACE ════════════════════════════ --}}
        <div class="gd_card">
            <div class="gd_card_head">
                <div class="gd_card_head_left">
                    <div class="gd_icon_wrap gd_icon_wrap--amber">
                        <i class="ti ti-shopping-bag"></i>
                    </div>
                    <div>
                        <p class="gd_card_title">Marketplace</p>
                        <p class="gd_card_subtitle">Listings &amp; trades</p>
                    </div>
                </div>
                @if($pendingTrades > 0)
                    <span class="gd_badge gd_badge--amber">{{ $pendingTrades }} pending</span>
                @endif
            </div>

            <div class="gd_card_body">
                <div class="gd_market_pair">
                    <div class="gd_market_stat">
                        <span class="gd_market_stat_val">{{ $activeListings }}</span>
                        <span class="gd_market_stat_lbl">Listings</span>
                    </div>
                    <div class="gd_market_stat">
                        <span class="gd_market_stat_val" style="{{ $pendingTrades > 0 ? 'color:var(--gd_amber)' : '' }}">{{ $pendingTrades }}</span>
                        <span class="gd_market_stat_lbl">Awaiting</span>
                    </div>
                </div>

                @if($pendingTrades > 0)
                    <div class="gd_trade_alert">
                        <i class="ti ti-clock"></i>
                        {{ $pendingTrades }} trade request(s) waiting for your response.
                    </div>
                @else
                    <p class="gd_empty">No pending trade requests.</p>
                @endif
            </div>

            <div class="gd_card_foot">
                <a href="{{ route('marketplace.market') }}" class="gd_link">
                    Go to Marketplace <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>


        {{-- ══ TOOL LIBRARY ═══════════════════════════ --}}
        <div class="gd_card">
            <div class="gd_card_head">
                <div class="gd_card_head_left">
                    <div class="gd_icon_wrap gd_icon_wrap--sky">
                        <i class="ti ti-tool"></i>
                    </div>
                    <div>
                        <p class="gd_card_title">Tool Library</p>
                        <p class="gd_card_subtitle">Borrow equipment</p>
                    </div>
                </div>
                @if($activeBookings > 0)
                    <span class="gd_badge gd_badge--sky">{{ $activeBookings }} active</span>
                @endif
            </div>

            <div class="gd_card_body">
                <div class="gd_tools_row">
                    <div class="gd_tools_icon">
                        <i class="ti ti-tool"></i>
                    </div>
                    <div>
                        <p class="gd_tools_val">{{ $activeBookings }}</p>
                        <p class="gd_tools_lbl">tools currently borrowed</p>
                    </div>
                </div>

                @if($activeBookings === 0)
                    <p class="gd_empty">No active bookings — browse the library.</p>
                @endif
            </div>

            <div class="gd_card_foot">
                <a href="{{ route('tools') }}" class="gd_link">
                    Browse tools <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>


        {{-- ══ VOLUNTEER ═══════════════════════════════ --}}
        <div class="gd_card">
            <div class="gd_card_head">
                <div class="gd_card_head_left">
                    <div class="gd_icon_wrap gd_icon_wrap--purple">
                        <i class="ti ti-heart-handshake"></i>
                    </div>
                    <div>
                        <p class="gd_card_title">Volunteer</p>
                        <p class="gd_card_subtitle">Community shifts</p>
                    </div>
                </div>
                @if($upcomingShifts->isNotEmpty())
                    <span class="gd_badge gd_badge--purple">{{ $upcomingShifts->count() }} upcoming</span>
                @endif
            </div>

            <div class="gd_card_body">
                @if($upcomingShifts->isNotEmpty())
                    <div style="display:flex;flex-direction:column;gap:7px;">
                        @foreach($upcomingShifts->take(3) as $shift)
                            <div class="gd_shift_item">
                                <div class="gd_shift_cal">
                                    <span class="gd_shift_day">{{ \Carbon\Carbon::parse($shift->date)->format('d') }}</span>
                                    <span class="gd_shift_month">{{ \Carbon\Carbon::parse($shift->date)->format('M') }}</span>
                                </div>
                                <div>
                                    <p class="gd_shift_title">{{ $shift->title ?? 'Volunteer Shift' }}</p>
                                    <p class="gd_shift_time">{{ $shift->start_time ?? '' }}</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <p class="gd_empty">No upcoming shifts — sign up to contribute!</p>
                @endif
            </div>

            <div class="gd_card_foot">
                <a href="{{ route('volunteer') }}" class="gd_link">
                    View all shifts <i class="ti ti-arrow-right"></i>
                </a>
            </div>
        </div>

    </div>{{-- /.gd_grid --}}

</div>{{-- /.gd_wrap --}}

@endsection