@extends('layouts.volunteer')

@section('title', 'Incidents')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-title">🚨 Incidents</div>
    <div class="page-sub">Manage reported incidents and issues in the garden</div>
  </div>
  <button class="btn btn-danger" onclick="document.getElementById('report-incident').classList.toggle('hidden')">🚨 Report Incident</button>
</div>

<style>.hidden { display: none; }</style>

<div id="report-incident" class="card hidden" style="margin-bottom:1.5rem">
  <h3 style="font-size:1rem; font-weight:700; margin-bottom:1rem; color:var(--green-800)">Report an Incident</h3>
  <form method="POST" action="{{ route('volunteer.incident.report') }}">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; max-width:600px">
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Title</label>
        <input class="input" type="text" name="title" required maxlength="255">
      </div>
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Description</label>
        <textarea class="input" name="description" rows="3" required></textarea>
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Location</label>
        <input class="input" type="text" name="location" required maxlength="255">
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Severity</label>
        <select class="input" name="severity">
          <option value="low">Low</option>
          <option value="medium" selected>Medium</option>
          <option value="high">High</option>
          <option value="critical">Critical</option>
        </select>
      </div>
    </div>
    <button class="btn btn-danger btn-sm" type="submit" style="margin-top:1rem">Report Incident</button>
  </form>
</div>

<div class="stats-row">
  <div class="stat-card"><div class="n" style="color:#dc2626">{{ $activeCount }}</div><div class="l">Active</div></div>
  <div class="stat-card"><div class="n" style="color:var(--earth-600)">{{ $investigationCount }}</div><div class="l">Under Investigation</div></div>
  <div class="stat-card"><div class="n" style="color:var(--green-600)">{{ $closedCount }}</div><div class="l">Closed</div></div>
</div>

@forelse($incidents as $incident)
  <div class="incident">
    <div class="incident-hd">
      <span class="sev sev-{{ $incident->severity === 'critical' ? 'critical' : ($incident->severity === 'high' || $incident->severity === 'major' ? 'major' : 'minor') }}">
        {{ ucfirst($incident->severity) }}
      </span>
      <div style="flex:1; margin-left:12px">
        <div style="font-weight:700; font-size:0.95rem; color:var(--green-800)">{{ $incident->title }}</div>
        <div style="font-size:0.76rem; color:var(--warm-gray)">INC-{{ str_pad($incident->id, 4, '0', STR_PAD_LEFT) }} · {{ $incident->created_at->format('d M Y') }}</div>
      </div>
      <span class="badge badge-{{ $incident->status === 'resolved' ? 'green' : ($incident->status === 'in_progress' ? 'orange' : 'red') }}">
        {{ str_replace('_', ' ', ucfirst($incident->status)) }}
      </span>
    </div>
    <div class="incident-body">{{ $incident->description }}</div>
    <div class="incident-meta">
      <span>📍 {{ $incident->location }}</span>
      <span>👤 Reported by: {{ $incident->reported_by }}</span>
      @if($incident->assigned_to)
        <span>🔧 Assigned: {{ $incident->assigned_to }}</span>
      @endif
    </div>
    @if(auth()->user()->isAdmin())
      <div class="incident-ft">
        <form method="POST" action="{{ route('volunteer.incident.update', $incident->id) }}" style="display:flex; gap:7px; flex-wrap:wrap">
          @csrf
          <select class="input" name="status" style="width:auto; font-size:0.8rem; padding:5px 10px">
            <option value="open" {{ $incident->status === 'open' ? 'selected' : '' }}>Open</option>
            <option value="in_progress" {{ $incident->status === 'in_progress' ? 'selected' : '' }}>In Progress</option>
            <option value="resolved" {{ $incident->status === 'resolved' ? 'selected' : '' }}>Resolved</option>
          </select>
          <input class="input" name="resolution_notes" placeholder="Resolution notes" style="width:auto; font-size:0.8rem; padding:5px 10px; flex:1">
          <button class="btn btn-primary btn-sm" type="submit">Update</button>
        </form>
      </div>
    @endif
  </div>
@empty
  <div class="card" style="text-align:center; padding:2rem">
    <div style="font-size:2rem; margin-bottom:0.5rem">✅</div>
    <p style="color:var(--warm-gray)">No incidents reported. Garden is safe! 🌿</p>
  </div>
@endforelse

@if(method_exists($incidents, 'links'))
  <div style="display:flex; justify-content:center; margin-top:1.25rem">{{ $incidents->links() }}</div>
@endif
@endsection
