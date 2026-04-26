<!DOCTYPE html>
<html>
<head>
    <title>Seed Bank</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    @vite([
        'resources/css/app.css',
        'resources/css/seedbank.css',
        'resources/js/app.js',
        'resources/js/seedbank.js'
    ])


</head>

<body>

<div class="container">
    <h1>Seed Bank</h1>
</div>
</body>
</html>
