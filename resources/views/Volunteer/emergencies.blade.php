@extends('layouts.volunteer')

@section('title', 'Emergency Alerts')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-title">⚠️ Alerts</div>
    <div class="page-sub">Notifications & emergency alerts</div>
  </div>
  <button class="btn btn-primary" onclick="document.getElementById('broadcast-form').classList.toggle('hidden')">+ Broadcast Alert</button>
</div>

<style>.hidden { display: none; }</style>

<div id="broadcast-form" class="card hidden" style="margin-bottom:1.5rem">
  <h3 style="font-size:1rem; font-weight:700; margin-bottom:1rem; color:var(--green-800)">Broadcast Emergency Alert</h3>
  <form method="POST" action="{{ route('volunteer.alert.broadcast') }}">
    @csrf
    <div style="display:grid; grid-template-columns:1fr 1fr; gap:1rem; max-width:600px">
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Title</label>
        <input class="input" type="text" name="title" required maxlength="255">
      </div>
      <div style="grid-column:1/-1">
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Message</label>
        <textarea class="input" name="message" rows="3" required></textarea>
      </div>
      <div>
        <label style="font-size:0.8rem; font-weight:600; display:block; margin-bottom:4px">Severity</label>
        <select class="input" name="severity" required>
          <option value="info">Info</option>
          <option value="warning" selected>Warning</option>
          <option value="critical">Critical</option>
        </select>
      </div>
    </div>
    <button class="btn btn-danger btn-sm" type="submit" style="margin-top:1rem">Broadcast Alert</button>
  </form>
</div>

@forelse($alerts as $alert)
  <div class="alert-item alert-{{ $alert->severity === 'critical' ? 'high' : ($alert->severity === 'warning' ? 'medium' : 'low') }}">
    <div class="alert-icon">
      {{ $alert->severity === 'critical' ? '🚨' : ($alert->severity === 'warning' ? '⚠️' : '📢') }}
    </div>
    <div style="flex:1">
      <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:6px">
        <div class="alert-title" style="color:{{ $alert->severity === 'critical' ? '#7f1d1d' : ($alert->severity === 'warning' ? '#78350f' : 'var(--green-800)') }}">
          {{ $alert->title }}
        </div>
        <span class="badge badge-{{ $alert->severity === 'critical' ? 'red' : ($alert->severity === 'warning' ? 'orange' : 'green') }}">
          {{ ucfirst($alert->severity) }}
        </span>
      </div>
      <div class="alert-body">{{ $alert->message }}</div>
      <div class="alert-time">{{ $alert->created_at->diffForHumans() }}</div>
      @if($alert->is_active)
        <div class="alert-actions">
          <form method="POST" action="{{ route('volunteer.alert.resolve', $alert->id) }}">
            @csrf
            <button class="btn btn-outline btn-sm" type="submit">✓ Resolve</button>
          </form>
        </div>
      @endif
    </div>
  </div>
@empty
  <div class="card" style="text-align:center; padding:2rem">
    <div style="font-size:2rem; margin-bottom:0.5rem">🔔</div>
    <p style="color:var(--warm-gray)">No alerts at this time.</p>
  </div>
@endforelse

@if(method_exists($alerts, 'links'))
  <div style="display:flex; justify-content:center; margin-top:1.25rem">{{ $alerts->links() }}</div>
@endif
@endsection
