@extends('layouts.app')

@section('page-title', 'My Seed Bank')

@section('content')

<div class="grid grid-cols-3 gap-4">

    {{-- Credits --}}
    <div class="p-4 bg-white shadow rounded">
        <h3 class="font-bold">Credits</h3>
        <p class="text-2xl">{{ auth()->user()->seedBank_credits }}</p>
    </div>

    {{-- Withdrawn seeds --}}
    <div class="col-span-2 bg-white shadow rounded p-4">
        <h3 class="font-bold mb-2">My Seeds</h3>

        @foreach($seeds as $seed)
            <div class="border-b py-2">
                {{ $seed['seed_type'] }} - {{ $seed['quantity'] }}
            </div>
        @endforeach
    </div>

</div>

@endsection