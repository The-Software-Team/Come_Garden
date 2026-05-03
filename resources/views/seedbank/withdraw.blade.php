@extends('layouts.app')

@section('page-title', '🌾 Withdraw Seeds')

@section('content')

<form method="POST" action="{{ route('seedbank.withdraw') }}">
    @csrf

    <select class="input" name="seed_type">
        @foreach($seeds as $seed)
            <option value="{{ $seed['type'] }}">
                {{ $seed['seed_type'] }}
            </option>
        @endforeach
    </select>

    <input class="input" type="number" name="quantity" placeholder="Quantity">

    <button class="btn">Withdraw</button>
</form>

@endsection