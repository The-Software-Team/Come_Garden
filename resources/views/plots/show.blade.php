@extends('layouts.app')

@section('page-title', "Plot #{$plot->id}")

@section('content')
<div class="plot-detail">

    <div class="panel main">
        <h2>Plot Info</h2>

        <p>Status: {{ $plot->status }}</p>
        <p>Soil: {{ $plot->soil_quality }}</p>
    </div>
</div>

@endsection