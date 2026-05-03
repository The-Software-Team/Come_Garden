@extends('layouts.app')

@section('page-title', '💰 My Seed Wallet')

@section('content')

<div class="panel">
    <h3>Your Credits</h3>
    <p>{{ $credits }}</p>
</div>

@endsection