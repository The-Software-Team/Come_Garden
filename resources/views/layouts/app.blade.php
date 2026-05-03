<!DOCTYPE html>
<html>
<head>
    <title>@yield('title', 'Garden System')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([
        'resources/css/app.css'
    ])
    @yield('styles')
</head>
    @yield('dynamics')
<body>
    @include('partials.navbar')


    <div class="container">
        @yield('content')
    </div>

</body>
</html>