@extends('layouts.guest')

@section('content')

<h2>Email Verification</h2>

<p style="font-size:13px;">
    Check your email for a verification link.
</p>

@if (session('status') == 'verification-link-sent')
    <p style="color:green; font-size:13px;">
        New link sent!
    </p>
@endif

<form method="POST" action="{{ route('verification.send') }}">
    @csrf
    <button class="btn">Resend Email</button>
</form>

<form method="POST" action="{{ route('logout') }}" style="margin-top:10px;">
    @csrf
    <button class="link" type="submit">Logout</button>
</form>

@endsection