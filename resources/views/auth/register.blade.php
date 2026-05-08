<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Join Come-Garden</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;1,400&family=DM+Sans:wght@300;400;500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/tabler-icons.min.css">

    <style>
        /* Reuse all auth tokens from login.blade.php — same variables */
        :root {
            --cream:        #f5f0e8;
            --cream-dark:   #ede5d5;
            --cream-darker: #ddd0b8;
            --ink:          #1c1a17;
            --ink-mid:      #3d3a34;
            --ink-light:    #7a7469;
            --forest:       #2a5c1e;
            --forest-mid:   #3d7a2f;
            --forest-light: #5a9e48;
            --forest-pale:  #e2eeda;
            --forest-bg:    #f1f7ec;
            --forest-border:rgba(42,92,30,.2);
            --forest-focus: rgba(42,92,30,.12);
            --red:          #7b1f1f;
            --red-bg:       #fceaea;
            --font-display: 'Playfair Display', Georgia, serif;
            --font-sans:    'DM Sans', system-ui, sans-serif;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            min-height: 100vh;
            background: var(--cream);
            font-family: var(--font-sans);
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 200 200' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.04'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 100; opacity: 0.4;
        }

        /* ── Left panel ── */
        .auth_left {
            background: var(--forest);
            display: flex; flex-direction: column;
            justify-content: space-between;
            padding: 48px;
            position: relative; overflow: hidden;
        }
        .auth_left::before {
            content: ''; position: absolute;
            top: -100px; left: -100px;
            width: 500px; height: 500px; border-radius: 50%;
            background: radial-gradient(circle, rgba(255,255,255,.07) 0%, transparent 65%);
        }
        .auth_left::after {
            content: ''; position: absolute;
            bottom: -120px; right: -80px;
            width: 400px; height: 400px; border-radius: 50%;
            background: radial-gradient(circle, rgba(196,150,10,.15) 0%, transparent 65%);
        }
        .auth_left_logo {
            display: flex; align-items: center; gap: 10px;
            text-decoration: none; position: relative; z-index: 1;
        }
        .auth_left_logo_mark {
            width: 34px; height: 34px; border-radius: 8px;
            background: rgba(255,255,255,.15);
            display: flex; align-items: center; justify-content: center;
        }
        .auth_left_logo_mark i { font-size: 17px; color: var(--cream); }
        .auth_left_logo_name {
            font-family: var(--font-display); font-size: 19px; font-weight: 600;
            color: var(--cream);
        }
        .auth_left_logo_name span { color: #a8d98e; }

        /* Perks list */
        .auth_left_perks {
            position: relative; z-index: 1;
        }
        .auth_left_perks_title {
            font-family: var(--font-display);
            font-size: clamp(20px, 2vw, 27px);
            font-weight: 600; font-style: italic;
            color: var(--cream);
            line-height: 1.3; margin-bottom: 28px;
        }
        .auth_left_perks_title strong { font-style: normal; color: #a8d98e; }
        .auth_perk_list { display: flex; flex-direction: column; gap: 14px; }
        .auth_perk {
            display: flex; align-items: flex-start; gap: 12px;
        }
        .auth_perk_icon {
            width: 32px; height: 32px;
            border-radius: 8px;
            background: rgba(255,255,255,.1);
            display: flex; align-items: center; justify-content: center;
            flex-shrink: 0;
        }
        .auth_perk_icon i { font-size: 15px; color: rgba(245,240,232,.8); }
        .auth_perk_text { flex: 1; }
        .auth_perk_title {
            font-size: 13px; font-weight: 500;
            color: var(--cream); margin-bottom: 2px;
        }
        .auth_perk_sub { font-size: 12px; color: rgba(245,240,232,.5); line-height: 1.4; }

        .auth_left_foot {
            font-size: 12px; color: rgba(245,240,232,.4);
            position: relative; z-index: 1;
        }

        /* ── Right panel ── */
        .auth_right {
            display: flex; flex-direction: column;
            align-items: center; justify-content: center;
            padding: 48px;
            overflow-y: auto;
        }
        .auth_form_wrap { width: 100%; max-width: 420px; }

        .auth_form_header { margin-bottom: 28px; }
        .auth_form_eyebrow {
            font-size: 11px; font-weight: 500;
            letter-spacing: .1em; text-transform: uppercase;
            color: var(--forest); margin-bottom: 8px;
            display: flex; align-items: center; gap: 6px;
        }
        .auth_form_title {
            font-family: var(--font-display);
            font-size: 28px; font-weight: 600;
            color: var(--ink);
            letter-spacing: -0.02em; margin-bottom: 6px;
        }
        .auth_form_sub { font-size: 14px; color: var(--ink-light); }
        .auth_form_sub a { color: var(--forest); text-decoration: none; font-weight: 500; }
        .auth_form_sub a:hover { text-decoration: underline; }

        /* Errors */
        .auth_errors {
            background: var(--red-bg);
            border: 1px solid rgba(123,31,31,.2);
            border-radius: 8px;
            padding: 12px 14px; margin-bottom: 20px;
        }
        .auth_errors_title { font-size: 12px; font-weight: 600; color: var(--red); margin-bottom: 6px; }
        .auth_errors ul { list-style: none; display: flex; flex-direction: column; gap: 3px; }
        .auth_errors li { font-size: 12px; color: var(--red); display: flex; align-items: center; gap: 6px; }
        .auth_errors li::before { content: ''; width: 4px; height: 4px; border-radius: 50%; background: var(--red); flex-shrink: 0; }

        /* Form */
        .auth_form { display: flex; flex-direction: column; gap: 16px; }
        .auth_field { display: flex; flex-direction: column; gap: 6px; }
        .auth_label { font-size: 13px; font-weight: 500; color: var(--ink-mid); }

        .auth_input_wrap { position: relative; }
        .auth_input_icon {
            position: absolute; left: 12px; top: 50%;
            transform: translateY(-50%);
            font-size: 15px; color: var(--ink-light); pointer-events: none;
        }
        .auth_input {
            width: 100%; height: 44px;
            padding: 0 14px 0 40px;
            border: 1px solid var(--cream-darker); border-radius: 8px;
            background: white;
            font-family: var(--font-sans); font-size: 14px; color: var(--ink);
            outline: none; transition: border-color .15s, box-shadow .15s;
        }
        .auth_input:focus {
            border-color: var(--forest);
            box-shadow: 0 0 0 3px var(--forest-focus);
        }
        .auth_input.error { border-color: var(--red); box-shadow: 0 0 0 3px rgba(123,31,31,.1); }
        .auth_input_error {
            font-size: 12px; color: var(--red);
            display: flex; align-items: center; gap: 5px;
        }
        .auth_input_error i { font-size: 12px; }

        /* Password strength hint */
        .auth_hint {
            font-size: 11px; color: var(--ink-light);
            display: flex; align-items: center; gap: 5px;
        }
        .auth_hint i { font-size: 11px; }

        /* T&C note */
        .auth_terms {
            font-size: 12px; color: var(--ink-light); line-height: 1.5;
            text-align: center;
        }
        .auth_terms a { color: var(--forest); text-decoration: none; }
        .auth_terms a:hover { text-decoration: underline; }

        .auth_submit {
            height: 48px;
            background: var(--forest); color: var(--cream);
            border: none; border-radius: 8px;
            font-family: var(--font-sans); font-size: 14px; font-weight: 500;
            cursor: pointer;
            display: flex; align-items: center; justify-content: center; gap: 8px;
            transition: background .15s, transform .1s; width: 100%;
        }
        .auth_submit:hover { background: var(--forest-mid); transform: translateY(-1px); }
        .auth_submit:active { transform: none; }

        .auth_divider {
            display: flex; align-items: center; gap: 12px;
            font-size: 12px; color: var(--ink-light);
        }
        .auth_divider::before, .auth_divider::after {
            content: ''; flex: 1; height: 1px; background: var(--cream-darker);
        }

        .auth_back {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 13px; color: var(--ink-light);
            text-decoration: none; transition: color .15s;
            justify-content: center;
        }
        .auth_back:hover { color: var(--forest); }

        /* Two-column layout for name + role */
        .auth_row { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; }

        @media (max-width: 768px) {
            body { grid-template-columns: 1fr; }
            .auth_left { display: none; }
            .auth_right { padding: 32px 24px; }
            .auth_row { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>

    <!-- LEFT PANEL -->
    <div class="auth_left">
        <a href="{{ url('/') }}" class="auth_left_logo">
            <div class="auth_left_logo_mark"><i class="ti ti-plant"></i></div>
            <span class="auth_left_logo_name">Come<span>-Garden</span></span>
        </a>

        <div class="auth_left_perks">
            <p class="auth_left_perks_title">
                Everything you need to<br><strong>tend your plot</strong>
            </p>
            <div class="auth_perk_list">
                <div class="auth_perk">
                    <div class="auth_perk_icon"><i class="ti ti-map-2"></i></div>
                    <div class="auth_perk_text">
                        <p class="auth_perk_title">Your own allotment plot</p>
                        <p class="auth_perk_sub">Apply for a plot, track crops, and manage your growing season.</p>
                    </div>
                </div>
                <div class="auth_perk">
                    <div class="auth_perk_icon"><i class="ti ti-heart"></i></div>
                    <div class="auth_perk_text">
                        <p class="auth_perk_title">Live soil health tracking</p>
                        <p class="auth_perk_sub">Automated soil state based on your crop rotation and fertiliser use.</p>
                    </div>
                </div>
                <div class="auth_perk">
                    <div class="auth_perk_icon"><i class="ti ti-seeding"></i></div>
                    <div class="auth_perk_text">
                        <p class="auth_perk_title">Community seed bank</p>
                        <p class="auth_perk_sub">Exchange seeds, earn credits, and grow together with neighbours.</p>
                    </div>
                </div>
                <div class="auth_perk">
                    <div class="auth_perk_icon"><i class="ti ti-tool"></i></div>
                    <div class="auth_perk_text">
                        <p class="auth_perk_title">Shared tool library</p>
                        <p class="auth_perk_sub">Book garden equipment from the shared pool whenever you need it.</p>
                    </div>
                </div>
            </div>
        </div>

        <p class="auth_left_foot">
            © {{ date('Y') }} Come-Garden. A community allotment platform.
        </p>
    </div>

    <!-- RIGHT PANEL — FORM -->
    <div class="auth_right">
        <div class="auth_form_wrap">

            <div class="auth_form_header">
                <p class="auth_form_eyebrow">
                    <i class="ti ti-seeding" style="font-size:13px;"></i>
                    New member
                </p>
                <h1 class="auth_form_title">Join Come-Garden</h1>
                <p class="auth_form_sub">
                    Already a member?
                    <a href="{{ route('login') }}">Log in →</a>
                </p>
            </div>

            {{-- Validation errors --}}
            @if($errors->any())
                <div class="auth_errors">
                    <p class="auth_errors_title">Please fix the following:</p>
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('register') }}" class="auth_form">
                @csrf

                {{-- Name --}}
                <div class="auth_field">
                    <label class="auth_label" for="name">Full name</label>
                    <div class="auth_input_wrap">
                        <i class="ti ti-user auth_input_icon"></i>
                        <input id="name" type="text" name="name"
                               class="auth_input {{ $errors->has('name') ? 'error' : '' }}"
                               value="{{ old('name') }}"
                               placeholder="Your full name"
                               required autofocus autocomplete="name">
                    </div>
                    @error('name')
                        <span class="auth_input_error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                    @enderror
                </div>

                {{-- Email --}}
                <div class="auth_field">
                    <label class="auth_label" for="email">Email address</label>
                    <div class="auth_input_wrap">
                        <i class="ti ti-mail auth_input_icon"></i>
                        <input id="email" type="email" name="email"
                               class="auth_input {{ $errors->has('email') ? 'error' : '' }}"
                               value="{{ old('email') }}"
                               placeholder="you@example.com"
                               required autocomplete="username">
                    </div>
                    @error('email')
                        <span class="auth_input_error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                    @enderror
                </div>

                {{-- Password --}}
                <div class="auth_field">
                    <label class="auth_label" for="password">Password</label>
                    <div class="auth_input_wrap">
                        <i class="ti ti-lock auth_input_icon"></i>
                        <input id="password" type="password" name="password"
                               class="auth_input {{ $errors->has('password') ? 'error' : '' }}"
                               placeholder="At least 8 characters"
                               required autocomplete="new-password">
                    </div>
                    @error('password')
                        <span class="auth_input_error"><i class="ti ti-alert-circle"></i>{{ $message }}</span>
                    @else
                        <span class="auth_hint"><i class="ti ti-info-circle"></i> Minimum 8 characters.</span>
                    @enderror
                </div>

                {{-- Confirm password --}}
                <div class="auth_field">
                    <label class="auth_label" for="password_confirmation">Confirm password</label>
                    <div class="auth_input_wrap">
                        <i class="ti ti-lock-check auth_input_icon"></i>
                        <input id="password_confirmation" type="password" name="password_confirmation"
                               class="auth_input"
                               placeholder="Repeat your password"
                               required autocomplete="new-password">
                    </div>
                </div>

                <p class="auth_terms">
                    By joining, you agree to abide by the community garden rules
                    and our <a href="#">terms of use</a>.
                </p>

                <button type="submit" class="auth_submit">
                    <i class="ti ti-plant"></i>
                    Create my account
                </button>

                <div class="auth_divider">or</div>

                <a href="{{ url('/') }}" class="auth_back">
                    <i class="ti ti-arrow-left" style="font-size:13px;"></i>
                    Back to home
                </a>

            </form>
        </div>
    </div>

</body>
</html>