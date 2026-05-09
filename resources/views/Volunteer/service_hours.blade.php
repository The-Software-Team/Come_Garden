@extends('layouts.volunteer')

@section('title', 'Service Hours')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-title">⏱ Service Hours</div>
    <div class="page-sub">Full record of your volunteer hours and completed shifts</div>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('log-form').classList.toggle('hidden')">+ Log Hours</button>
</div>

<style>.hidden { display: none; }</style>

<div id="log-form" class="card hidden" style="margin-bottom:1.5rem">
  <h3 style="font-size:1rem; font-weight:700; margin-bottom:1rem; color:var(--green-800)">Log New Hours</h3>
  <form method="POST" action="{{ route('volunteer.hours.log') }}">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; max-width:500px">
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Assignment ID</label>
        <input class="input" type="number" name="assignment_id" required>
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Hours</label>
        <input class="input" type="number" step="0.5" min="0.5" max="12" name="hours" required>
      </div>
    </div>
    <button class="btn btn-primary btn-sm" type="submit" style="margin-top:1rem">Log Hours</button>
  </form>
</div>

<div class="stats-row">
  <div class="stat-card"><div class="n">{{ $totalHours }}</div><div class="l">Total Hours</div></div>
  <div class="stat-card"><div class="n">{{ $shiftsDone }}</div><div class="l">Shifts Done</div></div>
  <div class="stat-card"><div class="n">{{ $avgHoursPerShift }}</div><div class="l">Avg hrs / Shift</div></div>
  <div class="stat-card"><div class="n">{{ $thisMonthHours }}</div><div class="l">This Month</div></div>
</div>

<div class="card" style="margin-bottom:1.5rem">
  <div style="font-weight:700; margin-bottom:10px; font-size:0.9rem">🎯 Progress toward Annual Goal (100 hrs)</div>
  <div style="display:flex; justify-content:space-between; font-size:0.8rem; color:var(--warm-gray); margin-bottom:6px">
    <span>{{ $totalHours }} of 100 hours completed</span>
    <span style="font-weight:700; color:var(--green-600)">{{ min($totalHours, 100) }}%</span>
  </div>
  <div class="progress-bar tall"><div class="progress-fill" style="width:{{ min($totalHours, 100) }}%"></div></div>
  <div style="font-size:0.76rem; color:var(--warm-gray); margin-top:6px">{{ max(0, 100 - $totalHours) }} hours remaining</div>
</div>

<div class="card">
  <table class="data-table">
    <thead>
      <tr>
        <th>Date</th><th>Shift / Task</th><th>Category</th><th>Hours</th><th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($hoursLog as $entry)
        <tr>
          <td>{{ $entry['date'] }}</td>
          <td style="font-weight:600">{{ $entry['task'] }}</td>
          <td><span class="tag tag-{{ $entry['category'] }}">{{ ucfirst($entry['category']) }}</span></td>
          <td class="num-cell">{{ $entry['hours'] }}</td>
          <td><span class="badge badge-green">✓ Confirmed</span></td>
        </tr>
      @empty
        <tr><td colspan="5" style="text-align:center; padding:2rem; color:var(--warm-gray)">No hours logged yet.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection
