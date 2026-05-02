@extends('layouts.guest')

@section('content')

<h2>Register</h2>

<form method="POST" action="{{ route('register') }}">
    @csrf

    <div class="form-group">
        <label>Name</label>
        <input class="input" type="text" name="name" value="{{ old('name') }}">
    </div>

    <div class="form-group">
        <label>Email</label>
        <input class="input" type="email" name="email" value="{{ old('email') }}">
    </div>

    <div class="form-group">
        <label>Password</label>
        <input class="input" type="password" name="password">
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input class="input" type="password" name="password_confirmation">
    </div>

    <button class="btn">Create Account</button>

    <div style="margin-top:10px;">
        <a class="link" href="{{ route('login') }}">
            Already registered?
        </a>
    </div>
</form>

@endsection