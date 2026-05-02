<!DOCTYPE html>
<html>
<head>
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css'])
</head>

<body class="auth-body">

    <div class="auth-wrapper">

        <div class="auth-card">

            <div class="auth-logo">
                🌱 Garden Community
            </div>

            @yield('content')

        </div>

    </div>

</body>
</html>