<!DOCTYPE html>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Listing - Garden Community</title>

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

    h2 {
        color: #2e7d32;
        margin-bottom: 20px;
    }

    input, select, textarea {
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
    🌿 Garden Community - Marketplace
</div>

<div class="container">

<div class="card">
    <h2>Create Listing</h2>

    {{-- Success Message --}}
    @if(session('message'))
        <div class="success">{{ session('message') }}</div>
    @endif
<form method="POST" action="{{ route('market.store') }}">
    @csrf

<label>Item</label>
<input type="text" name="item" value="{{ old('item') }}">
@error('item')
    <div class="error">{{ $message }}</div>
@enderror

<label>Quantity</label>
<input type="number" name="quantity" min="1" value="{{ old('quantity', 1) }}">
@error('quantity')
    <div class="error">{{ $message }}</div>
@enderror

<label>Type</label>
<input type="text" name="type" value="{{ old('type') }}" placeholder="e.g. seed, tool, produce">
@error('type')
    <div class="error">{{ $message }}</div>
@enderror

<label>Request (Optional)</label>
<textarea name="request">{{ old('request') }}</textarea>
@error('request')
    <div class="error">{{ $message }}</div>
@enderror

<button type="submit">Create Listing</button>

</form>

</div>

</div>

</body>
</html>
