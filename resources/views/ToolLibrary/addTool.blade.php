<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Tool - Garden Community</title>

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
            width: 400px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        h2 {
            color: #2e7d32;
            margin-bottom: 20px;
        }

        input, select {
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
    🌿 Garden Community - Tool Library
</div>

<div class="container">

    <div class="card">
        <h2>Add New Tool</h2>

        {{-- Success Message --}}
        @if(session('message'))
            <div class="success">{{ session('message') }}</div>
        @endif

        <form method="POST" action="{{ route('tools.store') }}">
            @csrf

            <label>Tool Name</label>
            <input type="text" name="name" value="{{ old('name') }}">
            @error('name')
                <div class="error">{{ $message }}</div>
            @enderror

            <label>Usage Status</label>
            <select name="usage_status">
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
            </select>

            <label>Maintenance Threshold (hours)</label>
            <input type="number" name="maintenance_threshold_hours" value="{{ old('maintenance_threshold_hours', 100) }}">
            @error('maintenance_threshold_hours')
                <div class="error">{{ $message }}</div>
            @enderror

            <button type="submit">Add Tool</button>
        </form>
    </div>

</div>

</body>
</html>