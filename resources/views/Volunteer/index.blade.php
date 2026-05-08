@extends('layouts.volunteer')

@section('title', 'Volunteer Dashboard')

@section('content')
<div class="hero">
  <div class="hero-bg-leaf">🌿</div>
  <div class="hero-bg-sprout">🌱</div>
  <h1>Welcome back, {{ auth()->user()->name }}! 🌱</h1>
  <p>Your community garden volunteer portal — together we grow a greener tomorrow.</p>
  <div class="hero-stats">
    <div class="hero-stat"><div class="n">{{ $totalHours ?? 0 }}</div><div class="l">Hours Volunteered</div></div>
    <div class="hero-stat"><div class="n">{{ $completedShifts ?? 0 }}</div><div class="l">Shifts Completed</div></div>
    <div class="hero-stat"><div class="n">{{ $attendanceRate ?? 100 }}%</div><div class="l">Attendance Rate</div></div>
    <div class="hero-stat"><div class="n">{{ $memberLevel ?? 'Bronze' }}</div><div class="l">Member Level</div></div>
  </div>
</div>

<div class="stats-row">
  <div class="stat-card"><div class="n">{{ $upcomingCount ?? 0 }}</div><div class="l">Upcoming Shifts</div></div>
  <div class="stat-card"><div class="n">{{ $openProposals ?? 0 }}</div><div class="l">Open Proposals</div></div>
  <div class="stat-card"><div class="n">{{ $activeAlerts ?? 0 }}</div><div class="l">Active Alerts</div></div>
  <div class="stat-card"><div class="n">{{ $teamMembers ?? \App\Models\Member::count() }}</div><div class="l">Team Members</div></div>
</div>

<div class="two-col">
  <div>
    <div class="section-title">Upcoming Shifts</div>
    @forelse($upcomingShifts as $shift)
      <div class="shift-card">
        <div class="shift-hd">
          <div>
            <div class="shift-name">{{ $shift->start_date->format('l') }} Shift</div>
            <div class="shift-date">{{ $shift->start_date->format('D d M Y · H:i') }}</div>
          </div>
          <span class="badge badge-green">{{ $shift->status }}</span>
        </div>
        <div class="shift-body">
          @foreach($shift->tasks as $task)
            <div class="task-row">
              <span class="task-name">{{ $task->name }}</span>
              <span class="tag tag-{{ $task->category }}">{{ ucfirst($task->category) }}</span>
            </div>
          @endforeach
          @if($shift->tasks->isEmpty())
            <div class="task-row"><span class="task-name">No tasks assigned yet</span></div>
          @endif
        </div>
      </div>
    @empty
      <div class="card" style="text-align:center; padding:2rem;">
        <div style="font-size:2rem; margin-bottom:0.5rem">📋</div>
        <p style="color:var(--warm-gray)">No upcoming shifts. Check back soon!</p>
      </div>
    @endforelse
  </div>

  <div>
    <div class="section-title">Recent Activity</div>
    <div class="card">
      <div class="timeline">
        @forelse($recentActivity as $activity)
          <div class="tl">
            <div class="tl-date">{{ $activity['date'] }}</div>
            <div class="tl-name">{{ $activity['title'] }}</div>
            <div class="tl-desc">{{ $activity['description'] }}</div>
          </div>
        @empty
          <div class="tl">
            <div class="tl-date">No activity yet</div>
            <div class="tl-name">Start volunteering to see your activity here!</div>
          </div>
        @endforelse
      </div>
    </div>
  </div>
</div>

<div class="section-title">Quick Actions</div>
<div class="card-grid">
  <a class="card" href="{{ route('volunteer.hours') }}" style="cursor:pointer; text-decoration:none; color:inherit">
    <div class="card-header"><div class="card-icon" style="background:var(--green-100)">⏱</div><span class="badge badge-green">{{ $totalHours ?? 0 }} hrs</span></div>
    <div class="card-title">My Hours</div><div class="card-sub">Track service hours & shifts</div>
  </a>
  <a class="card" href="{{ route('volunteer.proposals') }}" style="cursor:pointer; text-decoration:none; color:inherit">
    <div class="card-header"><div class="card-icon" style="background:var(--earth-100)">📋</div><span class="badge badge-earth">{{ $openProposals ?? 0 }} pending</span></div>
    <div class="card-title">Proposals</div><div class="card-sub">Vote on community proposals</div>
  </a>
  <a class="card" href="{{ route('volunteer.access.logs') }}" style="cursor:pointer; text-decoration:none; color:inherit">
    <div class="card-header"><div class="card-icon" style="background:#e6f0fb">🔒</div><span class="badge badge-blue">Secure</span></div>
    <div class="card-title">Access Logs</div><div class="card-sub">Review security access records</div>
  </a>
  @if(auth()->user()->isAdmin())
    <a class="card" href="{{ route('admin.volunteer.alerts') }}" style="cursor:pointer; text-decoration:none; color:inherit">
      <div class="card-header"><div class="card-icon" style="background:#fffbf0">⚠️</div><span class="badge badge-red">{{ $activeAlerts ?? 0 }} new</span></div>
      <div class="card-title">Alerts</div><div class="card-sub">Admin & emergency notifications</div>
    </a>
  @endif
</div>

<div class="page-footer">Come Garden Volunteer Portal · © 2025 All rights reserved</div>
@endsection
