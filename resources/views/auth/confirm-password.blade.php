@extends('layouts.guest')

@section('content')

<h2>Confirm Password</h2>

<p style="font-size:13px; margin-bottom:15px;">
    This is a secure area. Please confirm your password before continuing.
</p>

<form method="POST" action="{{ route('password.confirm') }}">
    @csrf

    <div class="form-group">
        <label>Password</label>
        <input class="input" type="password" name="password" required autocomplete="current-password">
    </div>

    @error('password')
        <p style="color:red; font-size:12px;">{{ $message }}</p>
    @enderror

    <button class="btn">Confirm</button>
</form>

@endsection