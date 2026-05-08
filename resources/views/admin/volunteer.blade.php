@extends('layouts.volunteer')

@section('title', 'Manage Shifts')

@push('scripts')
<script>

function showShift(shift) {

    let tasksHtml = '';

    (shift.tasks || []).forEach(task => {
        tasksHtml += `
            <div class="info-box">
                <div class="info-label">${task.category}</div>
                <div class="info-value">${task.name}</div>
            </div>
        `;
    });

    document.getElementById('shift-details').innerHTML = `
        
        <div class="details-header">
            <h2>Shift #${shift.id}</h2>
            <span class="badge">${shift.status}</span>
        </div>

        <div class="grid-info">

            <div class="info-box">
                <div class="info-label">Start</div>
                <div class="info-value">${shift.start_date}</div>
            </div>

            <div class="info-box">
                <div class="info-label">End</div>
                <div class="info-value">${shift.end_date}</div>
            </div>

        </div>

        <h3>Tasks</h3>
        <div class="grid-info">
            ${tasksHtml}
        </div>

        <div class="assign-section">

            <form method="POST" action="{{ route('volunteer.shift.assign') }}">
                @csrf

                <input type="hidden" name="shift_id" value="${shift.id}">

                <input class="input" name="member_id" type="number" placeholder="Member ID" required>
                <input class="input" name="role" placeholder="Role (heavy/light)" value="light">

                <button type="submit" class="btn">
                    Assign Member
                </button>
            </form>

        </div>

        <div class="complete-section">

            <form method="POST" action="{{ route('volunteer.shift.complete') }}">
                @csrf

                <input type="hidden" name="assignment_id" value="${shift.id}">
                <input type="hidden" name="hours" value="2">

                <button type="submit" class="btn btn-success">
                    Mark Shift Completed
                </button>
            </form>

        </div>
    `;
}

</script>
@endpush


@section('content')

@if(session('success'))
    <div class="alert alert--success">
        {{ session('success') }}
    </div>
@endif

<div class="grid grid-cols-12 gap-6">

    {{-- LEFT: Shifts --}}
    <div class="col-span-4">

        <h2>🧑‍🌾 Shifts</h2>

        <div class="seed-list">

            @foreach($shifts as $shift)
                <div class="seed-list-item"
                     onclick='showShift(@json($shift))'>

                    Shift #{{ $shift['id'] }}
                    <small>({{ $shift['status'] }})</small>
                </div>
            @endforeach

        </div>

        <hr>

        <h3>➕ Create Shift</h3>

        <form method="POST" action="{{ route('admin.volunteer.shift.create') }}">
            @csrf

            <input class="input" type="datetime-local" name="start_date" required>
            <input class="input" type="number" name="duration_days" placeholder="Duration (days)" required>

            <button class="btn" type="submit">
                Create Shift
            </button>
        </form>

    </div>

    {{-- RIGHT: DETAILS --}}
    <div class="col-span-8">

        <div class="panel">

            <div id="shift-details">
                <div class="empty-state">
                    <h3>Select a Shift</h3>
                    <p>Choose a shift to manage tasks and assignments.</p>
                </div>
            </div>

        </div>

    </div>

</div>

@endsection
