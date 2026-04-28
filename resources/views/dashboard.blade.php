<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Garden Dashboard</title>
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
            display: flex;
            justify-content: space-between;
        }

        .container {
            padding: 30px;
        }

        .card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            max-width: 500px;
            box-shadow: 0 3px 10px rgba(0,0,0,0.1);
        }

        .logout {
            color: white;
            text-decoration: none;
            background: #c62828;
            padding: 6px 12px;
            border-radius: 6px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <div>🌿 Garden Community</div>

    <div>
        {{ auth()->user()->name }}

        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button class="logout" type="submit">Logout</button>
        </form>
    </div>
</div>

<div class="container">
    <div class="card">
        <h3>Welcome back 🌱</h3>

        <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
        <p><strong>Email:</strong> {{ auth()->user()->email }}</p>

        <hr>

        <p>You are successfully logged in to the Garden Community system.</p>
    </div>
</div>

</body>
</html>