@extends('layouts.guest')

@section('content')

<h2>Reset Password</h2>

<p style="font-size:13px;">
    Enter your email and we’ll send a reset link.
</p>

<form method="POST" action="{{ route('password.email') }}">
    @csrf

    <div class="form-group">
        <label>Email</label>
        <input class="input" type="email" name="email">
    </div>

    <button class="btn">Send Reset Link</button>

</form>

@endsection
