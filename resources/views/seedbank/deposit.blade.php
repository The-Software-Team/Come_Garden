<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Seed Bank</title>

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
        width: 420px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.1);
    }

    h1, h2 {
        color: #2e7d32;
    }

    input {
        width: 100%;
        padding: 10px;
        margin-top: 6px;
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

    .success {
        color: green;
        margin-bottom: 10px;
    }

    .error {
        color: red;
        font-size: 13px;
    }
</style>

</head>

<body>

<div class="navbar">
    🌱 Seed Bank
</div>

<div class="container">

    <div class="card">

        <h2>Deposit Seeds</h2>

        {{-- Success Message --}}
        @if(session('message'))
            <div class="success">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('seedbank.store') }}">
            @csrf

            <label>Seed Type</label>
            <input type="text" name="seed_type" value="{{ old('seed_type') }}" required>
            @error('seed_type')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Quantity</label>
            <input type="number" name="quantity" value="{{ old('quantity') }}" required>
            @error('quantity')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Viability (%)</label>
            <input type="number" name="viability" min="0" max="100" value="{{ old('viability') }}" required>
            @error('viability')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Origin (Optional)</label>
            <input type="text" name="origin" value="{{ old('origin') }}">
            @error('origin')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Age (Optional)</label>
            <input type="number" name="age" value="{{ old('age') }}">
            @error('age')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Deposit</button>
        </form>

    </div>

</div>

</body>
</html>