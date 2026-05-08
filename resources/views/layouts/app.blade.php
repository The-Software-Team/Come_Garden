<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Garden System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([
        'resources/css/app.css',
        'resources/js/app.js'
    ])

    @stack('styles')
</head>

<body>

@if(request()->is('seedbank*'))
    <div class="seedbank_top_nav">

        <a href="{{ route('seedbank.profile') }}"
           class="seedbank_nav_link {{ request()->routeIs('seedbank.profile') ? 'active' : '' }}">
            <i class="ti ti-user"></i>
            Profile
        </a>

        <a href="{{ route('seedbank.browse') }}"
           class="seedbank_nav_link {{ request()->routeIs('seedbank.browse') ? 'active' : '' }}">
            <i class="ti ti-seedling"></i>
            Browse
        </a>

        <a href="{{ route('seedbank.deposit') }}"
           class="seedbank_nav_link {{ request()->routeIs('seedbank.deposit') ? 'active' : '' }}">
            <i class="ti ti-plus"></i>
            Deposit
        </a>

    </div>
@endif

    <div class="container">
        @yield('content')
    </div>

    @stack('scripts')

</body>
</html>