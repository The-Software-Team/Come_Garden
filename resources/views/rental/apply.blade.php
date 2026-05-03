<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <title>Apply for Plot - Garden Community</title>

<style>
    body {
        font-family: Arial;
        background: #f1f8e9;
        margin: 0;
    }

    .navbar {
        background: #2e7d32;
        color: white;
        padding: 15px 20px;
    }

    .container {
        padding: 30px;
        display: flex;
        justify-content: center;
    }

    .card {
        background: white;
        padding: 25px;
        border-radius: 10px;
        width: 500px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    h2 {
        color: #2e7d32;
        margin-bottom: 20px;
    }

    select {
        width: 100%;
        padding: 10px;
        margin-top: 8px;
        margin-bottom: 15px;
        border: 1px solid #ccc;
        border-radius: 6px;
    }

    button {
        width: 100%;
        padding: 10px;
        background: #2e7d32;
        color: white;
        border: none;
        border-radius: 6px;
        cursor: pointer;
    }

    button:hover {
        background: #1b5e20;
    }

    .error {
        color: red;
        font-size: 13px;
    }

    .success {
        color: green;
        margin-bottom: 10px;
    }
</style>

</head>
<body>

<div class="navbar">
    🌿 Garden Community - Plot Rental
</div>

<div class="container">

<div class="card">
    <h2>Apply for a Plot</h2>

{{-- Success Message --}}
@if(session('message'))
    <div class="success">{{ session('message') }}</div>
@endif

<form method="POST" action="{{ route('rental.store') }}">
    @csrf

{{-- Select Plot --}}
<label>Select Plot</label>
<select name="plot_id">
    <option value="">-- Choose a Plot --</option>
    @foreach($plots as $plot)
        <option value="{{ $plot->id }}" {{ old('plot_id') == $plot->id ? 'selected' : '' }}>
            Plot #{{ $plot->id }} - {{ $plot->size ?? 'Standard Size' }}
        </option>
    @endforeach
</select>
@error('plot_id')
    <div class="error">{{ $message }}</div>
@enderror

{{-- Share Selection --}}
<label>Share</label>
<select name="share">
    <option value="">-- Select Share --</option>
    <option value="1" {{ old('share') == '1' ? 'selected' : '' }}>Full (1)</option>
    <option value="0.5" {{ old('share') == '0.5' ? 'selected' : '' }}>Half (0.5)</option>
</select>
@error('share')
    <div class="error">{{ $message }}</div>
@enderror

<button type="submit">Apply</button>



</form>

</div>

</div>

<form method="POST" action="{{ route('rental.run') }}">
    @csrf
    <input value="1" name="plot_id">
    <input value="1" name="season_id">


{{-- Select Plot --}}
<label>Select Plot</label>
<button type="submit">Rent</button>
</body>
</html>
