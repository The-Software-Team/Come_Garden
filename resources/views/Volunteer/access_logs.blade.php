@extends('layouts.volunteer')

@section('title', 'Access Logs')

@section('content')
<div class="page-hd">
  <div>
    <div class="page-title">🔒 Access Logs</div>
    <div class="page-sub">Monitor all system access and login activity</div>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card"><div class="n" style="color:var(--green-600)">{{ $totalEntries }}</div><div class="l">Total Entries (30d)</div></div>
  <div class="stat-card"><div class="n" style="color:#dc2626">{{ $exitCount }}</div><div class="l">Exits</div></div>
</div>

<div style="background:white; border:1px solid var(--border); border-radius:var(--radius); overflow:hidden; box-shadow:var(--shadow)">
  <div style="display:flex; gap:1rem; padding:10px 16px; background:var(--green-50); border-bottom:1px solid var(--border); font-size:0.78rem; font-weight:700; color:var(--green-800)">
    <span style="min-width:120px">Time</span>
    <span style="min-width:120px">Member</span>
    <span style="flex:1">Event</span>
    <span>Location</span>
  </div>

  @forelse($logs as $log)
    @php $entry = (object) $log; @endphp
    <div class="log-row">
      <div class="log-dot" style="background:{{ $entry->action === 'entry' ? 'var(--green-400)' : ($entry->action === 'exit' ? 'var(--warm-gray)' : 'var(--earth-400)') }}"></div>
      <div class="log-time">{{ \Carbon\Carbon::parse($entry->accessed_at)->format('d M · H:i') }}</div>
      <div class="log-user">{{ $entry->member_id }}</div>
      <div class="log-event">✅ {{ ucfirst($entry->action) }} — Gate: {{ $entry->gate_location }}</div>
      <div class="log-ip">{{ $entry->gate_code_used }}</div>
    </div>
  @empty
    <div style="text-align:center; padding:2rem; color:var(--warm-gray)">No access logs found.</div>
  @endforelse
</div>

@if(method_exists($logs, 'links'))
  <div style="display:flex; justify-content:center; gap:8px; margin-top:1.25rem; align-items:center">
    {{ $logs->links() }}
  </div>
@endif
@endsection
