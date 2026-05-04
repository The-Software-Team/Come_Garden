@extends('layouts.app')

@section('title', 'My Volunteer Work')

@section('styles')
@vite([
    'resources/css/domain/volunteer.css'
])
@endsection

@section('dynamics')
<script>

function showAssignment(a) {

    document.getElementById('assignment-details').innerHTML = `

        <div class="details-header">
            <h2>${a.task_name}</h2>
            <span class="badge">${a.status}</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Shift ID</div>
                <div class="info-value">${a.shift_id}</div>
            </div>

            <div class="info-box">
                <div class="info-label">Hours</div>
                <div class="info-value">${a.hours ?? 2}</div>
            </div>

        </div>

        <div class="complete-section">

            <form method="POST" action="{{ route('volunteer.shift.complete') }}">
                @csrf

                <input type="hidden" name="shift_id" value="${a.shift_id}">

                <button type="submit" class="btn btn-success">
                    Mark Completed
                </button>
            </form>

        </div>

        <hr>

        <div class="swap-section">

            <h3>Request Swap</h3>

            <form method="POST" action="{{ route('volunteer.swap.request') }}">
                @csrf

                <input type="hidden" name="assignment_id" value="${a.id}">
                <input type="hidden" name="shift_id" value="${a.shift_id}">

                <input class="input" name="target_id" placeholder="Target Member ID" required>

                <textarea class="input" name="reason" placeholder="Reason (optional)"></textarea>

                <button type="submit" class="btn">
                    Request Swap
                </button>

            </form>

        </div>
    `;
}

</script>
@endsection


@section('content')

@if(session('success'))
    <div class="alert alert--success">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: My Assignments --}}
    <div class="col-span-4">

        <h2>📋 My Tasks</h2>

        <div class="seed-list">

            @foreach($assignments as $a)
                <div class="seed-list-item"
                     onclick='showAssignment(@json($a))'>

                    {{ $a['task_name'] }}
                    <small>({{ $a['status'] }})</small>
                </div>
            @endforeach

        </div>

    </div>

    {{-- RIGHT --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="assignment-details">
                <div class="empty-state">
                    <h3>Select a Task</h3>
                    <p>View your assignment and request swaps or completion.</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection