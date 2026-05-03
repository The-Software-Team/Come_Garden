@extends('layouts.app')

@section('page-title', '🌾 Seed Bank')

@section('content')

<div class="grid">

    <div class="panel">
        <h3>Seeds</h3>
        @foreach($seeds as $seed)
            <div>{{ $seed['seed_type'] }} - {{ $seed['quantity'] }}</div>
        @endforeach
    </div>

    <div class="panel">
        <h3>Your Credits</h3>
        <p>{{ $credits }}</p>
    </div>

</div>

@endsection