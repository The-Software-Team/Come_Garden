@extends('layouts.app')

@section('page-title', '🌱 Deposit Seeds')

@section('content')

<form method="POST" action="{{ route('seedbank.deposit.store') }}">
    @csrf

    <input class="input" name="seed_type" placeholder="Seed type">
    <input class="input" name="quantity" type="number" placeholder="Quantity">
    <input class="input" name="viability" type="number" placeholder="Viability %">
    <input class="input" name="age" type="number" placeholder="Age">
    <input class="input" name="origin" placeholder="Origin">
    <select class="input" name="owner_type">
        <option value="inventory">My Inventory</option>
        <option value="market">Market</option>
    </select>

    <button class="btn">Deposit</button>
</form>

@endsection