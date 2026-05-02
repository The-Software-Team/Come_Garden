@extends('layouts.guest')

@section('content')

<h2>Set New Password</h2>

<form method="POST" action="{{ route('password.store') }}">
    @csrf

    <input type="hidden" name="token" value="{{ $request->route('token') }}">

    <div class="form-group">
        <label>Email</label>
        <input class="input" type="email" name="email" value="{{ $request->email }}">
    </div>

    <div class="form-group">
        <label>New Password</label>
        <input class="input" type="password" name="password">
    </div>

    <div class="form-group">
        <label>Confirm Password</label>
        <input class="input" type="password" name="password_confirmation">
    </div>

    <button class="btn">Reset Password</button>

</form>

@endsection