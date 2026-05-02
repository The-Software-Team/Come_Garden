@extends('layouts.guest')

@section('content')

<h2>Login</h2>

<form method="POST" action="{{ route('login') }}">
    @csrf

    <div class="form-group">
        <label>Email</label>
        <input class="input" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input class="input" type="password" name="password">
    </div>

    <div class="form-group">
        <label>
            <input type="checkbox" name="remember">
            Remember me
        </label>
    </div>

    <button class="btn">Login</button>

    <div style="margin-top:10px;">
        <a class="link" href="{{ route('password.request') }}">
            Forgot password?
        </a>
    </div>
</form>

@endsection