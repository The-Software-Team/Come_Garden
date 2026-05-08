<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Come-Garden — Your Community Allotment</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400;1,600&family=Lora:ital,wght@0,400;0,500;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        /* ══════════════════════════════════════════════════════════
           COME-GARDEN  ·  Landing Page
           Aesthetic: Organic Editorial — botanical journal meets
           cooperative community spirit.
        ══════════════════════════════════════════════════════════ */

        :root {
            --cream:        #f5f0e8;
            --cream-dark:   #ede5d5;
            --cream-darker: #e0d4be;
            --ink:          #1c1a17;
            --ink-mid:      #3d3a34;
            --ink-light:    #7a7469;
            --forest:       #2a5c1e;
            --forest-mid:   #3d7a2f;
            --forest-light: #5a9e48;
            --forest-pale:  #e2eeda;
            --forest-bg:    #f1f7ec;
            --rust:         #8b3a1a;
            --rust-light:   #c4622d;
            --gold:         #c4960a;
            --gold-light:   #e8c84a;
            --sky:          #2668a8;

            --font-display: 'Playfair Display', Georgia, serif;
            --font-body:    'Lora', Georgia, serif;
            --font-sans:    'DM Sans', system-ui, sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html { scroll-behavior: smooth; }

        body {
            background: var(--cream);
            color: var(--ink);
            font-family: var(--font-body);
            line-height: 1.6;
            overflow-x: hidden;
        }

        /* ── Noise texture overlay on body ── */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 1000;
            opacity: 0.4;
        }

        /* ════════════════════════════════════════════
           NAV
        ════════════════════════════════════════════ */
        .nav {
            position: sticky;
            top: 0;
            z-index: 500;
            background: rgba(245, 240, 232, 0.92);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--cream-darker);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 48px;
            height: 58px;
        }

        .nav_logo {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }
        .nav_logo_mark {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
        }
        .nav_logo_mark i { font-size: 16px; color: var(--cream); }
        .nav_logo_name {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.01em;
        }
        .nav_logo_name span { color: var(--forest); }

        .nav_links {
            display: flex; align-items: center; gap: 28px;
            list-style: none;
        }
        .nav_links a {
            font-family: var(--font-sans);
            font-size: 13px;
            font-weight: 500;
            color: var(--ink-light);
            text-decoration: none;
            transition: color .15s;
        }
        .nav_links a:hover { color: var(--forest); }

        .nav_actions { display: flex; align-items: center; gap: 10px; }

        .nav_login {
            height: 36px; padding: 0 18px;
            border: 1px solid var(--cream-darker);
            border-radius: 6px;
            background: transparent;
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 500;
            color: var(--ink-mid);
            text-decoration: none;
            display: inline-flex; align-items: center;
            transition: all .15s;
        }
        .nav_login:hover { background: var(--cream-dark); }

        .nav_register {
            height: 36px; padding: 0 18px;
            border: none; border-radius: 6px;
            background: var(--forest);
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 500;
            color: var(--cream);
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s;
        }
        .nav_register:hover { background: var(--forest-mid); }
        .nav_register i { font-size: 13px; }

        /* ── Authenticated nav state ── */
        .nav_user {
            display: flex;
            align-items: center;
            gap: 8px;
            padding: 5px 12px 5px 6px;
            border: 1px solid var(--cream-darker);
            border-radius: 6px;
            background: var(--cream-dark);
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 500;
            color: var(--ink-mid);
        }
        .nav_avatar {
            width: 26px; height: 26px;
            border-radius: 6px;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
            font-size: 11px; font-weight: 700;
            color: var(--cream); flex-shrink: 0;
        }
        .nav_dashboard {
            height: 36px; padding: 0 16px;
            background: var(--forest);
            color: var(--cream);
            border: none; border-radius: 6px;
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 500;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 6px;
            transition: background .15s;
        }
        .nav_dashboard:hover { background: var(--forest-mid); }
        .nav_dashboard i { font-size: 13px; }
        .nav_logout_btn {
            height: 36px; padding: 0 14px;
            border: 1px solid var(--cream-darker);
            border-radius: 6px;
            background: transparent;
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 500;
            color: var(--ink-light);
            cursor: pointer;
            display: inline-flex; align-items: center; gap: 6px;
            transition: all .15s;
        }
        .nav_logout_btn:hover {
            background: #fceaea;
            color: #7b1f1f;
            border-color: rgba(123,31,31,.2);
        }
        .nav_logout_btn i { font-size: 13px; }

        /* ════════════════════════════════════════════
           HERO
        ════════════════════════════════════════════ */
        .hero {
            min-height: calc(100vh - 58px);
            display: grid;
            grid-template-columns: 1fr 1fr;
            position: relative;
            overflow: hidden;
        }

        /* Left — text side */
        .hero_left {
            padding: 80px 64px 80px 80px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            z-index: 2;
        }

        .hero_eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--forest);
            margin-bottom: 22px;
            opacity: 0;
            animation: fade-up .6s .1s ease forwards;
        }
        .hero_eyebrow::before {
            content: '';
            display: block;
            width: 24px; height: 1px;
            background: var(--forest);
        }

        .hero_headline {
            font-family: var(--font-display);
            font-size: clamp(44px, 5.5vw, 72px);
            font-weight: 700;
            line-height: 1.07;
            color: var(--ink);
            letter-spacing: -0.02em;
            margin-bottom: 24px;
            opacity: 0;
            animation: fade-up .7s .2s ease forwards;
        }
        .hero_headline em {
            font-style: italic;
            color: var(--forest);
        }

        .hero_body {
            font-family: var(--font-body);
            font-size: 17px;
            color: var(--ink-light);
            line-height: 1.7;
            max-width: 440px;
            margin-bottom: 36px;
            opacity: 0;
            animation: fade-up .7s .3s ease forwards;
        }

        .hero_actions {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
            opacity: 0;
            animation: fade-up .7s .4s ease forwards;
        }

        .btn_primary {
            height: 50px; padding: 0 28px;
            background: var(--forest);
            color: var(--cream);
            border: none; border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s, transform .12s;
        }
        .btn_primary:hover { background: var(--forest-mid); transform: translateY(-1px); }
        .btn_primary i { font-size: 15px; }

        .btn_ghost {
            height: 50px; padding: 0 24px;
            background: transparent;
            color: var(--ink-mid);
            border: 1.5px solid var(--cream-darker);
            border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all .15s;
        }
        .btn_ghost:hover { border-color: var(--forest); color: var(--forest); }

        .hero_trust {
            margin-top: 40px;
            display: flex;
            align-items: center;
            gap: 20px;
            opacity: 0;
            animation: fade-up .7s .5s ease forwards;
        }
        .hero_trust_stat {
            display: flex;
            flex-direction: column;
        }
        .hero_trust_num {
            font-family: var(--font-display);
            font-size: 22px;
            font-weight: 600;
            color: var(--ink);
        }
        .hero_trust_lbl {
            font-family: var(--font-sans);
            font-size: 11px;
            color: var(--ink-light);
            text-transform: uppercase;
            letter-spacing: .06em;
        }
        .hero_trust_divider {
            width: 1px;
            height: 32px;
            background: var(--cream-darker);
        }

        /* Right — botanical illustration side */
        .hero_right {
            position: relative;
            overflow: hidden;
            background: var(--forest-bg);
        }

        /* Large decorative circle */
        .hero_right::before {
            content: '';
            position: absolute;
            top: -80px; right: -120px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(90,158,72,.18) 0%, transparent 70%);
            pointer-events: none;
        }

        .hero_plot_grid {
            position: absolute;
            inset: 0;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-template-rows: repeat(5, 1fr);
            gap: 6px;
            padding: 40px;
            opacity: .55;
        }
        .hero_plot_cell {
            border-radius: 4px;
            background: var(--forest-pale);
            border: 1px solid rgba(42,92,30,.12);
            position: relative;
            overflow: hidden;
            animation: cell-grow .5s ease backwards;
        }
        .hero_plot_cell:nth-child(1)  { animation-delay: .05s; }
        .hero_plot_cell:nth-child(2)  { animation-delay: .10s; }
        .hero_plot_cell:nth-child(3)  { animation-delay: .15s; }
        .hero_plot_cell:nth-child(4)  { animation-delay: .20s; }
        .hero_plot_cell:nth-child(5)  { animation-delay: .10s; }
        .hero_plot_cell:nth-child(6)  { animation-delay: .15s; background: rgba(90,158,72,.22); }
        .hero_plot_cell:nth-child(7)  { animation-delay: .20s; }
        .hero_plot_cell:nth-child(8)  { animation-delay: .25s; background: rgba(90,158,72,.14); }
        .hero_plot_cell:nth-child(9)  { animation-delay: .15s; background: rgba(90,158,72,.28); }
        .hero_plot_cell:nth-child(10) { animation-delay: .20s; }
        .hero_plot_cell:nth-child(11) { animation-delay: .25s; background: rgba(139,58,26,.1); }
        .hero_plot_cell:nth-child(12) { animation-delay: .30s; background: rgba(90,158,72,.18); }
        .hero_plot_cell:nth-child(13) { animation-delay: .20s; }
        .hero_plot_cell:nth-child(14) { animation-delay: .25s; background: rgba(90,158,72,.34); }
        .hero_plot_cell:nth-child(15) { animation-delay: .30s; }
        .hero_plot_cell:nth-child(16) { animation-delay: .35s; background: rgba(196,150,10,.12); }
        .hero_plot_cell:nth-child(17) { animation-delay: .25s; background: rgba(90,158,72,.2); }
        .hero_plot_cell:nth-child(18) { animation-delay: .30s; }
        .hero_plot_cell:nth-child(19) { animation-delay: .35s; background: rgba(90,158,72,.26); }
        .hero_plot_cell:nth-child(20) { animation-delay: .40s; }

        /* The feature card floating over the grid */
        .hero_float_card {
            position: absolute;
            bottom: 52px; left: 40px; right: 40px;
            background: rgba(245, 240, 232, 0.96);
            backdrop-filter: blur(8px);
            border: 1px solid var(--cream-darker);
            border-radius: 12px;
            padding: 20px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
            box-shadow: 0 8px 32px rgba(28,26,23,.1), 0 2px 8px rgba(28,26,23,.06);
            opacity: 0;
            transform: translateY(16px);
            animation: fade-up .6s .8s ease forwards;
        }
        .hero_float_icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: var(--forest-pale);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .hero_float_icon i { font-size: 20px; color: var(--forest); }
        .hero_float_info { flex: 1; }
        .hero_float_title {
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 600;
            color: var(--ink); margin: 0 0 2px;
        }
        .hero_float_sub {
            font-family: var(--font-sans);
            font-size: 11px; color: var(--ink-light); margin: 0;
        }
        .hero_float_badge {
            padding: 4px 10px;
            border-radius: 999px;
            background: var(--forest-pale);
            color: var(--forest);
            font-family: var(--font-sans);
            font-size: 11px; font-weight: 600;
            flex-shrink: 0;
        }

        /* ════════════════════════════════════════════
           DIVIDER — botanical rule
        ════════════════════════════════════════════ */
        .rule {
            display: flex;
            align-items: center;
            gap: 16px;
            padding: 0 80px;
            margin: 64px 0;
        }
        .rule::before, .rule::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--cream-darker);
        }
        .rule_center {
            font-size: 18px;
            color: var(--forest);
            display: flex; align-items: center; gap: 8px;
        }

        /* ════════════════════════════════════════════
           FEATURES SECTION
        ════════════════════════════════════════════ */
        .features {
            padding: 0 80px 80px;
        }

        .section_label {
            font-family: var(--font-sans);
            font-size: 11px;
            font-weight: 500;
            letter-spacing: .12em;
            text-transform: uppercase;
            color: var(--forest);
            margin-bottom: 12px;
            display: flex; align-items: center; gap: 8px;
        }
        .section_label::before {
            content: '';
            display: block;
            width: 20px; height: 1px;
            background: var(--forest);
        }

        .section_headline {
            font-family: var(--font-display);
            font-size: clamp(30px, 3.5vw, 44px);
            font-weight: 700;
            line-height: 1.1;
            color: var(--ink);
            letter-spacing: -0.02em;
            margin-bottom: 48px;
            max-width: 540px;
        }
        .section_headline em { font-style: italic; color: var(--forest); }

        .features_grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .feature_card {
            background: var(--cream-dark);
            border: 1px solid var(--cream-darker);
            border-radius: 12px;
            padding: 28px 26px;
            position: relative;
            overflow: hidden;
            transition: border-color .2s, transform .2s, box-shadow .2s;
        }
        .feature_card:hover {
            border-color: rgba(42,92,30,.3);
            transform: translateY(-3px);
            box-shadow: 0 12px 40px rgba(28,26,23,.08);
        }
        .feature_card::after {
            content: '';
            position: absolute;
            bottom: 0; left: 0; right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--forest), var(--forest-light));
            transform: scaleX(0);
            transform-origin: left;
            transition: transform .3s ease;
        }
        .feature_card:hover::after { transform: scaleX(1); }

        .feature_icon {
            width: 44px; height: 44px;
            border-radius: 10px;
            background: var(--forest-pale);
            display: flex; align-items: center; justify-content: center;
            margin-bottom: 18px;
        }
        .feature_icon i { font-size: 20px; color: var(--forest); }
        .feature_icon--amber { background: #fef3c7; }
        .feature_icon--amber i { color: var(--gold); }
        .feature_icon--sky   { background: #e8f2fb; }
        .feature_icon--sky i { color: var(--sky); }
        .feature_icon--rust  { background: #fceaea; }
        .feature_icon--rust i { color: var(--rust); }
        .feature_icon--purple { background: #f5f3ff; }
        .feature_icon--purple i { color: #5b21b6; }

        .feature_title {
            font-family: var(--font-display);
            font-size: 18px;
            font-weight: 600;
            color: var(--ink);
            margin-bottom: 8px;
        }
        .feature_desc {
            font-family: var(--font-body);
            font-size: 14px;
            color: var(--ink-light);
            line-height: 1.65;
        }

        /* ════════════════════════════════════════════
           HOW IT WORKS
        ════════════════════════════════════════════ */
        .how {
            background: var(--forest);
            padding: 80px;
            position: relative;
            overflow: hidden;
        }

        /* Background texture */
        .how::before {
            content: '';
            position: absolute;
            top: -200px; right: -200px;
            width: 600px; height: 600px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.06) 0%, transparent 70%);
            pointer-events: none;
        }

        .how .section_label { color: var(--forest-light); }
        .how .section_label::before { background: var(--forest-light); }
        .how .section_headline { color: var(--cream); }
        .how .section_headline em { color: var(--gold-light); }

        .how_steps {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 40px;
            position: relative;
            z-index: 1;
        }

        /* Connector line */
        .how_steps::before {
            content: '';
            position: absolute;
            top: 28px; left: 28px; right: 28px;
            height: 1px;
            background: rgba(255,255,255,.15);
            z-index: 0;
        }

        .how_step { position: relative; z-index: 1; }

        .how_step_num {
            width: 56px; height: 56px;
            border-radius: 50%;
            background: rgba(255,255,255,.1);
            border: 1px solid rgba(255,255,255,.2);
            display: flex; align-items: center; justify-content: center;
            font-family: var(--font-display);
            font-size: 20px; font-weight: 700;
            color: var(--cream);
            margin-bottom: 18px;
        }
        .how_step_title {
            font-family: var(--font-display);
            font-size: 17px; font-weight: 600;
            color: var(--cream);
            margin-bottom: 8px;
        }
        .how_step_desc {
            font-family: var(--font-body);
            font-size: 14px;
            color: rgba(245,240,232,.65);
            line-height: 1.6;
        }

        /* ════════════════════════════════════════════
           SOIL / PLOT FEATURE CALLOUT
        ════════════════════════════════════════════ */
        .callout {
            padding: 80px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 80px;
            align-items: center;
        }

        .callout_text .section_headline { margin-bottom: 20px; }

        .callout_body {
            font-family: var(--font-body);
            font-size: 16px;
            color: var(--ink-light);
            line-height: 1.7;
            margin-bottom: 28px;
        }

        .callout_checks { display: flex; flex-direction: column; gap: 12px; margin-bottom: 32px; }
        .callout_check {
            display: flex;
            align-items: center;
            gap: 12px;
            font-family: var(--font-sans);
            font-size: 14px;
            color: var(--ink-mid);
        }
        .callout_check_icon {
            width: 24px; height: 24px;
            border-radius: 50%;
            background: var(--forest-pale);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .callout_check_icon i { font-size: 12px; color: var(--forest); }

        /* Soil ring cluster (decorative) */
        .callout_visual {
            background: var(--forest-bg);
            border-radius: 16px;
            padding: 40px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 14px;
            border: 1px solid var(--cream-darker);
        }

        .mini_card {
            background: var(--cream);
            border: 1px solid var(--cream-darker);
            border-radius: 10px;
            padding: 16px;
        }
        .mini_card:first-child {
            grid-column: span 2;
            display: flex;
            align-items: center;
            gap: 14px;
        }
        .mini_card_label {
            font-family: var(--font-sans);
            font-size: 10px;
            text-transform: uppercase;
            letter-spacing: .07em;
            color: var(--ink-light);
            display: block; margin-bottom: 6px;
        }
        .mini_card_val {
            font-family: var(--font-display);
            font-size: 22px; font-weight: 600;
            color: var(--ink);
        }
        .mini_card_sub {
            font-family: var(--font-sans);
            font-size: 11px;
            color: var(--ink-light);
            margin-top: 2px;
        }

        /* Fake soil ring */
        .soil_ring_wrap { flex-shrink: 0; }
        .soil_ring_info { flex: 1; }
        .soil_ring_title {
            font-family: var(--font-sans);
            font-size: 13px; font-weight: 600;
            color: var(--ink); margin-bottom: 4px;
        }
        .soil_ring_desc {
            font-family: var(--font-sans);
            font-size: 12px; color: var(--ink-light);
            line-height: 1.5;
        }

        /* ════════════════════════════════════════════
           CTA BANNER
        ════════════════════════════════════════════ */
        .cta_banner {
            margin: 0 80px 80px;
            background: var(--ink);
            border-radius: 16px;
            padding: 60px 72px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 40px;
            position: relative;
            overflow: hidden;
        }
        .cta_banner::before {
            content: '';
            position: absolute;
            top: -60px; right: -60px;
            width: 300px; height: 300px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(90,158,72,.2) 0%, transparent 70%);
        }
        .cta_banner::after {
            content: '';
            position: absolute;
            bottom: -80px; left: 200px;
            width: 400px; height: 400px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(196,150,10,.1) 0%, transparent 70%);
        }

        .cta_text { position: relative; z-index: 1; }
        .cta_title {
            font-family: var(--font-display);
            font-size: clamp(26px, 3vw, 38px);
            font-weight: 700;
            color: var(--cream);
            line-height: 1.1;
            margin-bottom: 10px;
            letter-spacing: -0.02em;
        }
        .cta_title em { font-style: italic; color: var(--gold-light); }
        .cta_sub {
            font-family: var(--font-body);
            font-size: 16px;
            color: rgba(245,240,232,.6);
        }
        .cta_actions {
            display: flex;
            gap: 12px;
            flex-shrink: 0;
            position: relative; z-index: 1;
            flex-wrap: wrap;
        }
        .cta_btn_primary {
            height: 50px; padding: 0 28px;
            background: var(--forest-light);
            color: var(--cream);
            border: none; border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: background .15s;
        }
        .cta_btn_primary:hover { background: var(--forest); }
        .cta_btn_ghost {
            height: 50px; padding: 0 24px;
            background: transparent;
            color: rgba(245,240,232,.7);
            border: 1px solid rgba(245,240,232,.2);
            border-radius: 8px;
            font-family: var(--font-sans);
            font-size: 14px; font-weight: 500;
            text-decoration: none;
            display: inline-flex; align-items: center; gap: 8px;
            transition: all .15s;
        }
        .cta_btn_ghost:hover { border-color: rgba(245,240,232,.5); color: var(--cream); }

        /* ════════════════════════════════════════════
           FOOTER
        ════════════════════════════════════════════ */
        .footer {
            background: var(--ink);
            padding: 48px 80px 32px;
        }
        .footer_top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding-bottom: 28px;
            border-bottom: 1px solid rgba(255,255,255,.1);
            gap: 20px;
            flex-wrap: wrap;
        }
        .footer_logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none;
        }
        .footer_logo_mark {
            width: 28px; height: 28px;
            border-radius: 6px;
            background: var(--forest);
            display: flex; align-items: center; justify-content: center;
        }
        .footer_logo_mark i { font-size: 14px; color: var(--cream); }
        .footer_logo_name {
            font-family: var(--font-display);
            font-size: 16px; font-weight: 600;
            color: var(--cream);
        }
        .footer_logo_name span { color: var(--forest-light); }

        .footer_links {
            display: flex; gap: 24px; list-style: none;
        }
        .footer_links a {
            font-family: var(--font-sans);
            font-size: 13px;
            color: rgba(245,240,232,.5);
            text-decoration: none;
            transition: color .15s;
        }
        .footer_links a:hover { color: var(--cream); }

        .footer_bottom {
            padding-top: 24px;
            font-family: var(--font-sans);
            font-size: 12px;
            color: rgba(245,240,232,.35);
            text-align: center;
        }

        /* ════════════════════════════════════════════
           ANIMATIONS
        ════════════════════════════════════════════ */
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(14px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes cell-grow {
            from { opacity: 0; transform: scale(.85); }
            to   { opacity: 1; transform: scale(1); }
        }

        /* Intersection observer classes */
        .reveal { opacity: 0; transform: translateY(20px); transition: opacity .6s ease, transform .6s ease; }
        .reveal.visible { opacity: 1; transform: none; }

        /* ════════════════════════════════════════════
           RESPONSIVE
        ════════════════════════════════════════════ */
        @media (max-width: 1024px) {
            .hero           { grid-template-columns: 1fr; min-height: auto; }
            .hero_right     { height: 400px; }
            .hero_left      { padding: 60px 48px; }
            .callout        { grid-template-columns: 1fr; padding: 60px 48px; gap: 40px; }
            .features,
            .how,
            .cta_banner     { padding-left: 48px; padding-right: 48px; }
            .cta_banner     { margin-left: 48px; margin-right: 48px; flex-direction: column; text-align: center; }
            .footer         { padding-left: 48px; padding-right: 48px; }
            .rule           { padding: 0 48px; }
        }
        @media (max-width: 768px) {
            .nav            { padding: 0 24px; }
            .nav_links      { display: none; }
            .hero_left      { padding: 48px 24px; }
            .features       { padding: 0 24px 60px; }
            .features_grid  { grid-template-columns: 1fr; }
            .how            { padding: 60px 24px; }
            .how_steps      { grid-template-columns: 1fr 1fr; }
            .how_steps::before { display: none; }
            .cta_banner     { margin: 0 24px 60px; padding: 40px 28px; }
            .footer         { padding: 40px 24px 24px; }
            .footer_top     { flex-direction: column; }
            .rule           { padding: 0 24px; }
        }
    </style>
</head>
<body>

    <!-- NAV -->
    <nav class="nav">
        <a href="/" class="nav_logo">
            <div class="nav_logo_mark"><i class="ti ti-plant"></i></div>
            <span class="nav_logo_name">Come<span>-Garden</span></span>
        </a>

        <ul class="nav_links">
            <li><a href="#features">Features</a></li>
            <li><a href="#how">How It Works</a></li>
            <li><a href="#plots">Your Plot</a></li>
        </ul>

        <div class="nav_actions">
            @auth
                {{-- Logged-in: show avatar chip, dashboard link, logout --}}
                <div class="nav_user">
                    <div class="nav_avatar">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </div>
                    {{ explode(' ', auth()->user()->name)[0] }}
                </div>
                <a href="{{ route('dashboard.member') }}" class="nav_dashboard">
                    <i class="ti ti-layout-dashboard"></i> Dashboard
                </a>
                <form method="POST" action="{{ route('logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" class="nav_logout_btn">
                        <i class="ti ti-logout"></i> Log out
                    </button>
                </form>
            @else
                {{-- Guest: show login + register --}}
                <a href="{{ route('login') }}" class="nav_login">Log in</a>
                <a href="{{ route('register') }}" class="nav_register">
                    <i class="ti ti-plant"></i> Join the Garden
                </a>
            @endauth
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="hero_left">
            <span class="hero_eyebrow">Come-Garden Platform</span>

            <h1 class="hero_headline">
                Your community<br>
                <em>garden, together.</em>
            </h1>

            <p class="hero_body">
                Manage your allotment plot, track soil health, share tools and seeds,
                and grow alongside your neighbours — all in one place.
            </p>

            <div class="hero_actions">
                <a href="{{ route('register') }}" class="btn_primary">
                    <i class="ti ti-plant"></i>
                    Claim your plot
                </a>
                <a href="#how" class="btn_ghost">
                    <i class="ti ti-arrow-down"></i>
                    Learn more
                </a>
            </div>

            <div class="hero_trust">
                <div class="hero_trust_stat">
                    <span class="hero_trust_num">120+</span>
                    <span class="hero_trust_lbl">Active plots</span>
                </div>
                <div class="hero_trust_divider"></div>
                <div class="hero_trust_stat">
                    <span class="hero_trust_num">400+</span>
                    <span class="hero_trust_lbl">Members</span>
                </div>
                <div class="hero_trust_divider"></div>
                <div class="hero_trust_stat">
                    <span class="hero_trust_num">8</span>
                    <span class="hero_trust_lbl">Seasons running</span>
                </div>
            </div>
        </div>

        <div class="hero_right">
            <!-- Allotment grid background -->
            <div class="hero_plot_grid">
                @for($i = 0; $i < 20; $i++)<div class="hero_plot_cell"></div>@endfor
            </div>

            <!-- Floating info card -->
            <div class="hero_float_card">
                <div class="hero_float_icon">
                    <i class="ti ti-heart"></i>
                </div>
                <div class="hero_float_info">
                    <p class="hero_float_title">Plot #42 — Soil Health</p>
                    <p class="hero_float_sub">Crop diversity detected · East Wing · Morning Sun</p>
                </div>
                <span class="hero_float_badge">Healthy 85%</span>
            </div>
        </div>
    </section>

    <!-- SECTION RULE -->
    <div class="rule">
        <div class="rule_center">
            <i class="ti ti-plant"></i>
            <i class="ti ti-seeding"></i>
            <i class="ti ti-leaf"></i>
        </div>
    </div>

    <!-- FEATURES -->
    <section class="features" id="features">
        <p class="section_label reveal">Everything you need</p>
        <h2 class="section_headline reveal">
            A full allotment<br><em>management toolkit</em>
        </h2>

        <div class="features_grid">
            <div class="feature_card reveal">
                <div class="feature_icon">
                    <i class="ti ti-map-2"></i>
                </div>
                <h3 class="feature_title">Plot Management</h3>
                <p class="feature_desc">
                    Browse and apply for allotment plots. View your zone, sun profile,
                    dimensions, and soil health — all tracked automatically.
                </p>
            </div>

            <div class="feature_card reveal" style="transition-delay:.08s;">
                <div class="feature_icon feature_icon--amber">
                    <i class="ti ti-heart"></i>
                </div>
                <h3 class="feature_title">Soil Health Tracking</h3>
                <p class="feature_desc">
                    Our system watches your crop history and fertiliser use to compute
                    a live soil state: healthy, recovering, depleted, or neutral.
                </p>
            </div>

            <div class="feature_card reveal" style="transition-delay:.16s;">
                <div class="feature_icon feature_icon--sky">
                    <i class="ti ti-seeding"></i>
                </div>
                <h3 class="feature_title">Seed Bank</h3>
                <p class="feature_desc">
                    Exchange and borrow seeds from the community vault. Credits, seed types,
                    and low-stock alerts keep the garden well-stocked for every season.
                </p>
            </div>

            <div class="feature_card reveal" style="transition-delay:.0s;">
                <div class="feature_icon feature_icon--rust">
                    <i class="ti ti-shield"></i>
                </div>
                <h3 class="feature_title">Infection Alerts</h3>
                <p class="feature_desc">
                    Report pests or disease on your plot. Neighbouring plots are
                    automatically notified so the whole garden can respond quickly.
                </p>
            </div>

            <div class="feature_card reveal" style="transition-delay:.08s;">
                <div class="feature_icon feature_icon--amber">
                    <i class="ti ti-tool"></i>
                </div>
                <h3 class="feature_title">Tool Library</h3>
                <p class="feature_desc">
                    Book shared garden equipment — spades, wheelbarrows, power tools.
                    Track availability in real time and get maintenance reminders.
                </p>
            </div>

            <div class="feature_card reveal" style="transition-delay:.16s;">
                <div class="feature_icon feature_icon--purple">
                    <i class="ti ti-heart-handshake"></i>
                </div>
                <h3 class="feature_title">Volunteer Shifts</h3>
                <p class="feature_desc">
                    Sign up for community work days, manage garden rotas, and
                    keep the shared spaces in great shape together.
                </p>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="how" id="how">
        <p class="section_label">Simple process</p>
        <h2 class="section_headline" style="margin-bottom:52px;">
            From application<br>to <em>harvest</em>
        </h2>

        <div class="how_steps">
            <div class="how_step">
                <div class="how_step_num">01</div>
                <h3 class="how_step_title">Join &amp; Browse</h3>
                <p class="how_step_desc">
                    Create your account, explore the plot map, and find an allotment
                    that fits your sun preference and size needs.
                </p>
            </div>
            <div class="how_step">
                <div class="how_step_num">02</div>
                <h3 class="how_step_title">Apply</h3>
                <p class="how_step_desc">
                    Submit a rental application with your experience level and
                    growing plans. The garden warden reviews and approves.
                </p>
            </div>
            <div class="how_step">
                <div class="how_step_num">03</div>
                <h3 class="how_step_title">Grow</h3>
                <p class="how_step_desc">
                    Log your crops, get personalised watering schedules, track soil
                    health, and borrow tools — everything in your dashboard.
                </p>
            </div>
            <div class="how_step">
                <div class="how_step_num">04</div>
                <h3 class="how_step_title">Share</h3>
                <p class="how_step_desc">
                    Trade surplus produce at the marketplace, contribute to the seed bank,
                    and volunteer on community days.
                </p>
            </div>
        </div>
    </section>

    <!-- CALLOUT — Soil tracking detail -->
    <section class="callout" id="plots">
        <div class="callout_text reveal">
            <p class="section_label">Intelligent tracking</p>
            <h2 class="section_headline">
                Your soil tells<br>a <em>story</em>
            </h2>
            <p class="callout_body">
                Come-Garden monitors your crop rotation history and fertiliser use to
                automatically compute a soil health state after every planting.
                Know when to rotate, when to add organic matter, and when you're
                in the clear — without guesswork.
            </p>
            <div class="callout_checks">
                <div class="callout_check">
                    <div class="callout_check_icon"><i class="ti ti-check"></i></div>
                    Healthy — good diversity, keep going
                </div>
                <div class="callout_check">
                    <div class="callout_check_icon"><i class="ti ti-refresh" style="color:var(--sky)"></i></div>
                    Recovering — organic fertiliser is working
                </div>
                <div class="callout_check">
                    <div class="callout_check_icon"><i class="ti ti-alert-circle" style="color:var(--gold)"></i></div>
                    Depleted — time to rotate crops
                </div>
            </div>
            <a href="{{ route('register') }}" class="btn_primary">
                <i class="ti ti-plant"></i>
                Start growing
            </a>
        </div>

        <div class="callout_visual reveal" style="transition-delay:.15s;">
            <!-- Fake plot detail card -->
            <div class="mini_card">
                <div class="soil_ring_wrap">
                    <svg width="60" height="60" viewBox="0 0 60 60">
                        <circle cx="30" cy="30" r="24" fill="none" stroke="#e2eeda" stroke-width="5"/>
                        <circle cx="30" cy="30" r="24" fill="none" stroke="#3d7a2f" stroke-width="5"
                                stroke-linecap="round"
                                stroke-dasharray="127.2 150.8"
                                transform="rotate(-90, 30, 30)"/>
                        <text x="30" y="35" text-anchor="middle" font-size="11" font-weight="700"
                              fill="#3d7a2f" font-family="DM Sans, sans-serif">85%</text>
                    </svg>
                </div>
                <div class="soil_ring_info">
                    <p class="soil_ring_title">Soil: Healthy</p>
                    <p class="soil_ring_desc">
                        3 different crops planted this season — great diversity!
                    </p>
                </div>
            </div>
            <div class="mini_card">
                <span class="mini_card_label">Crops Planted</span>
                <p class="mini_card_val">🍅 🥕 🫘</p>
                <p class="mini_card_sub">Tomatoes, Carrots, Beans</p>
            </div>
            <div class="mini_card">
                <span class="mini_card_label">Sun Profile</span>
                <p class="mini_card_val" style="font-size:17px;">☀️ Full Sun</p>
                <p class="mini_card_sub">Central zone · 10×20 m</p>
            </div>
            <div class="mini_card">
                <span class="mini_card_label">Neighbours</span>
                <p class="mini_card_val" style="font-size:17px;">4</p>
                <p class="mini_card_sub">All clear — no infections</p>
            </div>
            <div class="mini_card">
                <span class="mini_card_label">Winter Task</span>
                <p class="mini_card_val" style="font-size:13px;font-family:'DM Sans',sans-serif;">Turn &amp; Aerate</p>
                <p class="mini_card_sub">Soil preparation due</p>
            </div>
        </div>
    </section>

    <!-- CTA BANNER -->
    <div class="cta_banner reveal">
        <div class="cta_text">
            <h2 class="cta_title">Ready to <em>grow</em>?</h2>
            <p class="cta_sub">Join hundreds of gardeners already tending their plots.</p>
        </div>
        <div class="cta_actions">
            <a href="{{ route('register') }}" class="cta_btn_primary">
                <i class="ti ti-plant"></i>
                Join the Garden
            </a>
            <a href="{{ route('login') }}" class="cta_btn_ghost">
                Already a member? Log in
            </a>
        </div>
    </div>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="footer_top">
            <a href="/" class="footer_logo">
                <div class="footer_logo_mark"><i class="ti ti-plant"></i></div>
                <span class="footer_logo_name">Come<span>-Garden</span></span>
            </a>
            <ul class="footer_links">
                <li><a href="#features">Features</a></li>
                <li><a href="#how">How it works</a></li>
                <li><a href="{{ route('login') }}">Log in</a></li>
                <li><a href="{{ route('register') }}">Register</a></li>
            </ul>
        </div>
        <div class="footer_bottom">
            © {{ date('Y') }} Come-Garden. A community allotment management platform.
        </div>
    </footer>

    <script>
        // Intersection observer for .reveal elements
        const observer = new IntersectionObserver(entries => {
            entries.forEach(e => {
                if (e.isIntersecting) {
                    e.target.classList.add('visible');
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.12 });

        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
    </script>

</body>
</html>