{{--
    Shared Navbar Component
    Usage: automatically included in layouts/app.blade.php
    The active section is detected from the current route prefix.
--}}

@php
    $route = request()->route()?->getName() ?? '';

    // Detect which section we're in
    $inSeedbank     = str_starts_with($route, 'seedbank');
    $inMarketplace  = str_starts_with($route, 'marketplace');
    $inOps          = str_starts_with($route, 'volunteer');
    $inTools        = str_starts_with($route, 'tools');
    $inPlots        = str_starts_with($route, 'plots');
    $inAdmin        = str_starts_with($route, 'admin');
    $inRentals      = str_starts_with($route, 'rentals');

    // Helper: active class
    $active = fn(string $r) => request()->routeIs($r) ? 'nav_link--active' : '';
@endphp

<nav class="app_nav" role="navigation" aria-label="Main navigation">

    {{-- Brand --}}
    <a href="{{ route('dashboard.member') }}" class="app_nav_brand">
        <div class="app_nav_brand_icon">
            <i class="ti ti-plant-2" aria-hidden="true"></i>
        </div>
        <span class="app_nav_brand_name">Garden System</span>
    </a>

    <div class="app_nav_right">

        {{-- Member avatar / initials --}} 
        <div class="app_nav_avatar"
             aria-label="Signed in as {{ auth()->user()?->name }}">
            {{ strtoupper(substr(auth()->user()?->name ?? 'M', 0, 1)) }}{{ strtoupper(substr(explode(' ', auth()->user()?->name ?? 'M ')[1] ?? '', 0, 1)) }}
        </div>

    </div>

</nav>