<!DOCTYPE html>
<html>
<head>
    <title>Seed Bank</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.seedbank = {
            withdrawUrl: "{{ route('seedbank.withdraw') }}",
        };
    </script>

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

    <div id="messageBox"></div>

    <section class="card">

       <h2>Withdraw Seeds</h2>

        <form id="withdrawForm">

            <label>Member ID</label>
            <input type="number" name="member_id" required>

            <label>Seed Type</label>
            <input type="text" name="seed_type" required>

            <label>Quantity</label>
            <input type="number" name="quantity" required>

            <button type="submit">Deposit</button>
        </form>

    </section>

</div>
</body>
</html>

