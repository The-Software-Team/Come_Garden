<!DOCTYPE html>
<html>
<head>
    <title>Seed Bank</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <script>
        window.seedbank = {
            depositUrl: "{{ route('seedbank.deposit') }}",
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

    <!-- SUCCESS / ERROR FEEDBACK -->
    <div id="messageBox"></div>

    <!-- DEPOSIT FORM -->
    <section class="card">

       <h2>Deposit Seeds</h2>

        <form id="depositForm">

            <label>Member ID</label>
            <input type="number" name="member_id" required>

            <label>Seed Type</label>
            <input type="text" name="seed_type" required>

            <label>Quantity</label>
            <input type="number" name="quantity" required>

            <label>Viability (%)</label>
            <input type="number" name="viability" min="0" max="100" required>

            <label>Origin</label>
            <input type="text" name="origin">

            <label>Age</label>
            <input type="number" name="age">

            <button type="submit">Deposit</button>
        </form>

    </section>


</div>

</body>
</html>
