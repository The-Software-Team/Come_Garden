<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}">
<title>@yield('title', 'Volunteer Portal') — Come Garden</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
:root {
  --green-50: #f0faf2;
  --green-100: #d4f0db;
  --green-200: #a9e0b6;
  --green-400: #4daa62;
  --green-500: #3a9450;
  --green-600: #2d7a3e;
  --green-700: #245c30;
  --green-800: #1a4d26;
  --green-900: #0d2813;
  --earth-50: #faf7f0;
  --earth-100: #f0e8d0;
  --earth-200: #d9c89a;
  --earth-400: #b8963a;
  --earth-600: #8a6a1f;
  --earth-800: #5c4410;
  --cream: #fdfbf5;
  --warm-gray: #6b6455;
  --border: #e2ddd4;
  --text: #1a2e1d;
  --shadow: 0 2px 12px rgba(45,122,62,0.08);
  --shadow-hover: 0 8px 32px rgba(45,122,62,0.15);
  --radius: 12px;
  --radius-sm: 8px;
}
*, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }
body {
  font-family: 'DM Sans', sans-serif;
  background: var(--cream);
  color: var(--text);
  min-height: 100vh;
  font-size: 15px;
}

.topbar {
  background: var(--green-800);
  display: flex;
  align-items: center;
  justify-content: space-between;
  padding: 0 2rem;
  height: 62px;
  position: sticky;
  top: 0;
  z-index: 200;
  border-bottom: 3px solid var(--green-600);
}
.logo {
  display: flex; align-items: center; gap: 10px;
  font-family: 'Playfair Display', serif;
  font-size: 1.25rem;
  color: var(--green-100);
  text-decoration: none;
}
.logo-mark {
  width: 36px; height: 36px;
  background: var(--green-500);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 1.2rem;
  flex-shrink: 0;
}
.nav-links { display:flex; gap:2px; list-style:none; }
.nav-links li a {
  color: var(--green-200);
  text-decoration: none;
  padding: 7px 13px;
  border-radius: 6px;
  font-size: 0.82rem;
  font-weight: 500;
  transition: all .15s;
  display: flex; align-items: center; gap: 5px;
  white-space: nowrap;
}
.nav-links li a:hover { background: var(--green-700); color: white; }
.nav-links li a.active { background: var(--green-500); color: white; }
.user-pill {
  display: flex; align-items: center; gap: 8px;
  background: rgba(255,255,255,0.08);
  border: 1px solid rgba(255,255,255,0.15);
  padding: 5px 12px 5px 6px;
  border-radius: 20px;
  font-size: 0.82rem;
  color: var(--green-100);
}
.avatar {
  width: 28px; height: 28px;
  background: var(--earth-400);
  border-radius: 50%;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.68rem;
  font-weight: 700;
  color: white;
}

.layout {
  display: grid;
  grid-template-columns: 228px 1fr;
  min-height: calc(100vh - 62px);
}

.sidebar {
  background: white;
  border-right: 1px solid var(--border);
  padding: 1.25rem 0 1rem;
  position: sticky;
  top: 62px;
  height: calc(100vh - 62px);
  overflow-y: auto;
  display: flex;
  flex-direction: column;
}
.sb-group { margin-bottom: 1.5rem; }
.sb-label {
  font-size: 0.68rem;
  font-weight: 600;
  letter-spacing: 0.09em;
  text-transform: uppercase;
  color: var(--warm-gray);
  padding: 0 1.1rem;
  margin-bottom: 4px;
}
.sb-link {
  display: flex;
  align-items: center;
  gap: 10px;
  padding: 9px 1.1rem;
  color: var(--text);
  font-size: 0.87rem;
  font-weight: 500;
  border-left: 3px solid transparent;
  text-decoration: none;
  transition: all .15s;
}
.sb-link:hover { background: var(--green-50); color: var(--green-600); }
.sb-link.active { background: var(--green-50); border-left-color: var(--green-400); color: var(--green-700); }
.sb-icon {
  width: 30px; height: 30px;
  background: var(--earth-50);
  border-radius: 6px;
  display: flex; align-items: center; justify-content: center;
  font-size: 0.95rem;
  flex-shrink: 0;
}
.sb-link.active .sb-icon { background: var(--green-400); }
.sb-count {
  margin-left: auto;
  background: var(--earth-400);
  color: white;
  font-size: 0.68rem;
  font-weight: 700;
  padding: 2px 7px;
  border-radius: 10px;
  min-width: 20px;
  text-align: center;
}
.sb-count.danger { background: #dc2626; }
.sb-bottom { margin-top: auto; padding: 1rem 1.1rem; }
.goal-box {
  background: var(--green-50);
  border: 1px solid var(--green-200);
  border-radius: var(--radius);
  padding: 12px 14px;
}
.goal-box .goal-title { font-size: 0.78rem; font-weight: 700; color: var(--green-700); margin-bottom: 8px; }
.goal-box .goal-sub { font-size: 0.72rem; color: var(--warm-gray); margin-top: 5px; }

main { overflow-y: auto; padding: 1.75rem 2rem; }

.hero {
  background: var(--green-800);
  border-radius: var(--radius);
  padding: 2rem 2.5rem;
  color: white;
  margin-bottom: 1.75rem;
  position: relative;
  overflow: hidden;
}
.hero-bg-leaf {
  position: absolute;
  right: -40px; top: -40px;
  font-size: 11rem;
  opacity: 0.06;
  pointer-events: none;
  line-height: 1;
}
.hero-bg-sprout {
  position: absolute;
  left: 40px; bottom: -20px;
  font-size: 7rem;
  opacity: 0.07;
  pointer-events: none;
}
.hero h1 {
  font-family: 'Playfair Display', serif;
  font-size: 2rem;
  font-weight: 700;
  margin-bottom: 6px;
  line-height: 1.2;
}
.hero p { font-size: 0.9rem; opacity: 0.78; font-weight: 300; }
.hero-stats { display: flex; gap: 1rem; margin-top: 1.5rem; flex-wrap: wrap; }
.hero-stat {
  background: rgba(255,255,255,0.12);
  border: 1px solid rgba(255,255,255,0.18);
  border-radius: 8px;
  padding: 10px 18px;
}
.hero-stat .n { font-size: 1.5rem; font-weight: 700; }
.hero-stat .l { font-size: 0.72rem; opacity: 0.75; margin-top: 1px; }

.stats-row {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(130px, 1fr));
  gap: 1rem;
  margin-bottom: 1.75rem;
}
.stat-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.1rem;
  text-align: center;
  box-shadow: var(--shadow);
}
.stat-card .n { font-size: 1.85rem; font-weight: 700; color: var(--green-600); font-family: 'Playfair Display', serif; }
.stat-card .l { font-size: 0.75rem; color: var(--warm-gray); margin-top: 3px; }

.card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  padding: 1.4rem;
  box-shadow: var(--shadow);
  transition: transform .18s, box-shadow .18s;
}
.card:hover { transform: translateY(-2px); box-shadow: var(--shadow-hover); }
.card-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(230px, 1fr)); gap: 1rem; margin-bottom: 1.75rem; }
.card-icon { width: 42px; height: 42px; border-radius: 8px; display: flex; align-items: center; justify-content: center; font-size: 1.3rem; }
.card-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 10px; }
.card-title { font-size: 0.95rem; font-weight: 600; }
.card-sub { font-size: 0.8rem; color: var(--warm-gray); margin-top: 2px; }

.badge { display: inline-flex; align-items: center; gap: 4px; padding: 3px 9px; border-radius: 20px; font-size: 0.73rem; font-weight: 600; }
.badge-green { background: var(--green-100); color: var(--green-800); }
.badge-earth { background: var(--earth-100); color: var(--earth-800); }
.badge-red { background: #fce8e8; color: #8b1f1f; }
.badge-blue { background: #e6f0fb; color: #0c447c; }
.badge-gray { background: #f0ede8; color: #5c5245; }
.badge-orange { background: #fff3e0; color: #7c4200; }

.section-title {
  font-family: 'Playfair Display', serif;
  font-size: 1.2rem;
  color: var(--green-800);
  margin-bottom: 1rem;
  display: flex;
  align-items: center;
  gap: 8px;
}
.section-title::after { content:''; flex:1; height:1px; background:var(--border); }

.shift-card {
  background: white;
  border: 1px solid var(--border);
  border-radius: var(--radius);
  overflow: hidden;
  margin-bottom: 1rem;
  box-shadow: var(--shadow);
  transition: box-shadow .18s;
}
.shift-card:hover { box-shadow: var(--shadow-hover); }
.shift-hd {
  background: var(--green-50);
  padding: 12px 18px;
  display: flex; align-items: center; justify-content: space-between;
  border-bottom: 1px solid var(--border);
}
.shift-name { font-weight: 700; font-size: 0.95rem; color: var(--green-800); }
.shift-date { font-size: 0.78rem; color: var(--warm-gray); margin-top: 2px; }
.shift-body { padding: 12px 18px; }
.task-row {
  display: flex; align-items: center; justify-content: space-between;
  padding: 9px 0; border-bottom: 1px solid var(--border); font-size: 0.85rem;
}
.task-row:last-child { border-bottom: none; }
.task-name { display: flex; align-items: center; gap: 7px; font-weight: 500; }
.tag { display: inline-flex; align-items: center; gap: 3px; font-size: 0.7rem; padding: 2px 7px; border-radius: 4px; font-weight: 600; }
.tag-light { background: var(--green-100); color: var(--green-700); }
.tag-heavy { background: var(--earth-100); color: var(--earth-800); }

.btn {
  display: inline-flex; align-items: center; gap: 5px;
  padding: 8px 16px;
  border-radius: var(--radius-sm);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.85rem; font-weight: 600;
  cursor: pointer;
  transition: all .15s;
  border: none;
  text-decoration: none;
}
.btn-primary { background: var(--green-600); color: white; }
.btn-primary:hover { background: var(--green-800); }
.btn-outline { background: white; color: var(--green-600); border: 1.5px solid var(--green-400); }
.btn-outline:hover { background: var(--green-50); }
.btn-danger { background: #dc2626; color: white; }
.btn-danger:hover { background: #b91c1c; }
.btn-sm { padding: 5px 12px; font-size: 0.78rem; }

.progress-bar { width:100%; height:6px; background:var(--border); border-radius:3px; overflow:hidden; }
.progress-fill { height:100%; background:var(--green-400); border-radius:3px; }
.progress-bar.tall { height: 10px; }

.data-table { width:100%; border-collapse:collapse; }
.data-table th {
  background: var(--green-800); color: var(--green-100);
  padding: 11px 15px; font-size: 0.79rem; font-weight: 600; text-align: left;
}
.data-table td { padding: 11px 15px; font-size: 0.85rem; border-bottom: 1px solid var(--border); }
.data-table tr:hover td { background: var(--green-50); }
.data-table .num-cell { font-weight: 700; color: var(--green-600); }

.filter-bar { display:flex; gap:8px; align-items:center; margin-bottom:1.25rem; flex-wrap:wrap; }
.filter-bar select, .filter-bar input {
  padding: 7px 12px;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.83rem;
  color: var(--text);
  background: white;
  outline: none;
}
.filter-bar select:focus, .filter-bar input:focus { border-color: var(--green-400); }

.page-hd { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.5rem; }
.page-title { font-family:'Playfair Display',serif; font-size:1.65rem; color:var(--green-800); }
.page-sub { font-size:0.82rem; color:var(--warm-gray); margin-top:3px; }

.tabs { display:flex; gap:2px; border-bottom:2px solid var(--border); margin-bottom:1.5rem; }
.tab {
  padding: 9px 18px; font-size:0.85rem; font-weight:600;
  color: var(--warm-gray); cursor:pointer;
  border-bottom: 2px solid transparent; margin-bottom: -2px;
  transition: all .15s;
}
.tab:hover { color: var(--green-600); }
.tab.active { color: var(--green-600); border-bottom-color: var(--green-400); }

.proposal {
  background: white; border: 1px solid var(--border);
  border-radius: var(--radius); padding: 1.4rem;
  margin-bottom: 1.1rem; box-shadow: var(--shadow);
}
.proposal-title { font-size: 1rem; font-weight: 700; margin: 8px 0; color: var(--green-800); }
.proposal-desc { font-size: 0.85rem; color: var(--warm-gray); margin-bottom: 1rem; line-height: 1.65; }
.vote-bar { background: var(--border); border-radius: 8px; height: 9px; overflow: hidden; display: flex; margin: 8px 0; }
.vote-yes { background: var(--green-400); }
.vote-no { background: #e24b4a; }
.vote-neutral { background: var(--earth-200); }
.vote-meta { display:flex; gap:1rem; font-size:0.77rem; margin-bottom:4px; flex-wrap:wrap; }
.vote-meta .yes { color: var(--green-600); font-weight:600; }
.vote-meta .no { color: #dc2626; font-weight:600; }
.vote-meta .abs { color: var(--warm-gray); }
.vote-actions { display:flex; gap:7px; margin-top:12px; flex-wrap:wrap; }

.log-row {
  display:flex; align-items:center; gap:12px;
  padding: 11px 16px; border-bottom: 1px solid var(--border);
  font-size: 0.83rem; transition: background .12s;
}
.log-row:hover { background: var(--green-50); }
.log-dot { width:8px; height:8px; border-radius:50%; flex-shrink:0; }
.log-time { color:var(--warm-gray); font-size:0.76rem; min-width:120px; }
.log-user { font-weight:600; min-width:120px; }
.log-event { flex:1; }
.log-ip { color:var(--warm-gray); font-size:0.76rem; font-family:monospace; }

.alert-item {
  display:flex; gap:14px; padding:15px;
  border-radius: var(--radius); margin-bottom:10px; border:1px solid;
}
.alert-high { background:#fff5f5; border-color:#f0a0a0; }
.alert-medium { background:#fffbf0; border-color:#f0c870; }
.alert-low { background:var(--green-50); border-color:var(--green-200); }
.alert-icon { font-size:1.3rem; flex-shrink:0; margin-top:1px; }
.alert-title { font-weight:700; font-size:0.9rem; margin-bottom:4px; }
.alert-body { font-size:0.83rem; color:var(--warm-gray); line-height:1.55; }
.alert-time { font-size:0.73rem; color:var(--warm-gray); margin-top:4px; }
.alert-actions { display:flex; gap:7px; margin-top:10px; flex-wrap:wrap; }

.incident {
  background: white; border:1px solid var(--border);
  border-radius: var(--radius); overflow:hidden;
  margin-bottom:1rem; box-shadow:var(--shadow);
}
.incident-hd {
  display:flex; align-items:center; gap:12px;
  padding: 12px 18px; background:var(--earth-50);
  border-bottom: 1px solid var(--border);
}
.sev { padding:3px 9px; border-radius:4px; font-size:0.7rem; font-weight:700; }
.sev-critical { background:#7f1d1d; color:white; }
.sev-major { background:#dc2626; color:white; }
.sev-minor { background:var(--earth-400); color:white; }
.incident-body { padding:14px 18px; font-size:0.85rem; color:var(--warm-gray); line-height:1.65; }
.incident-meta {
  display:flex; gap:1rem; flex-wrap:wrap;
  font-size:0.76rem; color:var(--warm-gray);
  padding:10px 18px; border-top:1px solid var(--border);
}
.incident-ft {
  padding:11px 18px; background:var(--earth-50);
  border-top:1px solid var(--border);
  display:flex; gap:7px;
}

.timeline { padding-left:20px; border-left:2px solid var(--green-200); }
.tl { position:relative; padding:10px 0 10px 20px; }
.tl::before {
  content:''; position:absolute; left:-25px; top:16px;
  width:10px; height:10px; border-radius:50%;
  background:var(--green-400); border:2px solid white;
}
.tl-date { font-size:0.73rem; color:var(--warm-gray); }
.tl-name { font-weight:600; font-size:0.87rem; }
.tl-desc { font-size:0.8rem; color:var(--warm-gray); }

.notif-dot { position:relative; display:inline-flex; }
.notif-dot::after {
  content:''; position:absolute; top:1px; right:1px;
  width:7px; height:7px; border-radius:50%;
  background:#e24b4a; border:1.5px solid var(--green-800);
  animation:pulse 2s infinite;
}
@keyframes pulse {
  0%,100% { transform:scale(1); opacity:1; }
  50% { transform:scale(1.35); opacity:.65; }
}

.page-footer {
  margin-top:2.5rem; padding-top:1.5rem;
  border-top:1px solid var(--border);
  text-align:center; font-size:0.75rem; color:var(--warm-gray);
}

.two-col { display:grid; grid-template-columns:1fr 1fr; gap:1.5rem; margin-bottom:1.75rem; }
@media(max-width:820px){ .two-col{ grid-template-columns:1fr; } }

.kv-row { display:flex; justify-content:space-between; align-items:center; padding:8px 0; border-bottom:1px solid var(--border); font-size:0.84rem; }
.kv-row:last-child { border-bottom:none; }
.kv-key { color:var(--warm-gray); }
.kv-val { font-weight:600; }

.alert { padding: 12px 18px; border-radius: var(--radius-sm); margin-bottom: 1rem; font-size: 0.85rem; font-weight: 500; }
.alert--success { background: var(--green-100); color: var(--green-800); border: 1px solid var(--green-200); }

.input {
  padding: 7px 12px;
  border: 1px solid var(--border);
  border-radius: var(--radius-sm);
  font-family: 'DM Sans', sans-serif;
  font-size: 0.85rem;
  color: var(--text);
  background: white;
  outline: none;
  width: 100%;
  transition: border-color .15s;
}
.input:focus { border-color: var(--green-400); }

@media(max-width:768px){
  .layout{ grid-template-columns:1fr; }
  .sidebar{ display:none; }
  main{ padding:1rem; }
  .nav-links{ display:none; }
}
</style>
@stack('styles')
</head>
<body>

<header class="topbar">
  <a class="logo" href="{{ route('volunteer') }}">
    <div class="logo-mark">🌿</div>
    <span>Come Garden</span>
  </a>
  <nav>
    <ul class="nav-links">
      <li><a href="{{ route('volunteer') }}" class="{{ request()->routeIs('volunteer') ? 'active' : '' }}">🏡 Home</a></li>
      <li><a href="{{ route('volunteer.hours') }}" class="{{ request()->routeIs('volunteer.hours') ? 'active' : '' }}">⏱ My Hours</a></li>
      <li><a href="{{ route('volunteer.proposals') }}" class="{{ request()->routeIs('volunteer.proposals') ? 'active' : '' }}">🗳 Proposals</a></li>
      <li><a href="{{ route('volunteer.access.logs') }}" class="{{ request()->routeIs('volunteer.access.logs') ? 'active' : '' }}">🔒 Access Logs</a></li>
      @if(auth()->user()->isAdmin())
        <li><a href="{{ route('admin.volunteer.alerts') }}" class="{{ request()->routeIs('admin.volunteer.alerts') ? 'active' : '' }}" style="color:#fcd34d">⚠️ Alerts</a></li>
        <li><a href="{{ route('admin.volunteer.incidents') }}" class="{{ request()->routeIs('admin.volunteer.incidents') ? 'active' : '' }}" style="color:#fca5a5">🚨 Incidents</a></li>
      @endif
    </ul>
  </nav>
  <div class="user-pill">
    <div class="avatar">{{ substr(auth()->user()->name, 0, 2) }}</div>
    {{ auth()->user()->name }} &nbsp;▾
  </div>
</header>

<div class="layout">
  <aside class="sidebar">
    <div class="sb-group">
      <div class="sb-label">Volunteer</div>
      <a class="sb-link {{ request()->routeIs('volunteer') ? 'active' : '' }}" href="{{ route('volunteer') }}"><div class="sb-icon">🏡</div>Dashboard</a>
      <a class="sb-link {{ request()->routeIs('volunteer.hours') ? 'active' : '' }}" href="{{ route('volunteer.hours') }}"><div class="sb-icon">⏱</div>Service Hours</a>
      <a class="sb-link {{ request()->routeIs('volunteer.proposals') ? 'active' : '' }}" href="{{ route('volunteer.proposals') }}"><div class="sb-icon">🗳</div>Proposals</a>
    </div>
    <div class="sb-group">
      <div class="sb-label">Security</div>
      <a class="sb-link {{ request()->routeIs('volunteer.access.logs') ? 'active' : '' }}" href="{{ route('volunteer.access.logs') }}"><div class="sb-icon">🔒</div>Access Logs</a>
    </div>
    @if(auth()->user()->isAdmin())
    <div class="sb-group">
      <div class="sb-label">Administration</div>
      <a class="sb-link {{ request()->routeIs('admin.volunteer.alerts') ? 'active' : '' }}" href="{{ route('admin.volunteer.alerts') }}"><div class="sb-icon">⚠️</div>Alerts</a>
      <a class="sb-link {{ request()->routeIs('admin.volunteer.incidents') ? 'active' : '' }}" href="{{ route('admin.volunteer.incidents') }}"><div class="sb-icon">🚨</div>Incidents</a>
      <a class="sb-link" href="{{ route('admin.volunteer') }}"><div class="sb-icon">⚙️</div>Manage Shifts</a>
    </div>
    @endif
    <div class="sb-bottom">
      <div class="goal-box">
        <div class="goal-title">🎯 Monthly Goal</div>
        <div class="progress-bar">
          <div class="progress-fill" style="width:{{ min(($totalHours ?? 0) / 10 * 100, 100) }}%"></div>
        </div>
        <div class="goal-sub">{{ $totalHours ?? 0 }} of 10 hrs &nbsp;·&nbsp; {{ min(($totalHours ?? 0) / 10 * 100, 100) }}%</div>
      </div>
    </div>
  </aside>

  <main>
    @if(session('success'))
      <div class="alert alert--success">{{ session('success') }}</div>
    @endif
    @yield('content')
  </main>
</div>

@stack('scripts')
</body>
</html>
