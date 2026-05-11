<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Admin — Volunteer & Community Operations</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
:root {
  --soil:    #3B2A1A;
  --bark:    #5C3D20;
  --moss:    #2D5016;
  --fern:    #3E7021;
  --leaf:    #5A9A2E;
  --sage:    #8CB87A;
  --mist:    #D4E8C2;
  --cream:   #F7F3EC;
  --parch:   #EDE7DA;
  --warm-w:  #FDFAF4;
  --sun:     #E8A020;
  --dusk:    #C46820;
  --berry:   #8B2335;
  --sky:     #2E6B8A;
  --sky-lt:  #D0E8F2;
  --text-dark:  #1E1409;
  --text-body:  #3D2E1C;
  --text-muted: #7A6A54;
  --text-faint: #B0A090;
  --border:     rgba(90,60,20,0.12);
  --border-md:  rgba(90,60,20,0.22);
  --r-sm: 8px; --r-md: 14px; --r-lg: 20px;
  --shadow-sm: 0 2px 8px rgba(59,42,26,0.08);
}
*{box-sizing:border-box;margin:0;padding:0}
body { font-family: 'Nunito', sans-serif; background: var(--cream); color: var(--text-body); min-height: 100vh; }
.topbar { background: var(--bark); background-image: radial-gradient(ellipse at 20% 50%, rgba(92,61,32,0.3) 0%, transparent 60%), radial-gradient(ellipse at 80% 30%, rgba(59,42,26,0.4) 0%, transparent 50%); padding: 0 2.5rem; height: 66px; display: flex; align-items: center; justify-content: space-between; position: sticky; top:0; z-index:200; border-bottom: 2px solid rgba(255,255,255,0.08); }
.topbar-brand { display:flex; align-items:center; gap:14px; }
.brand-icon { width:42px; height:42px; border-radius:12px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; }
.brand-name { font-family:'Playfair Display',serif; font-size:18px; font-weight:600; color:#fff; letter-spacing:0.01em; }
.brand-sub { font-size:12px; color:rgba(255,255,255,0.6); margin-top:1px; }
.topbar-right { display:flex; align-items:center; gap:12px; }
.nav-link { color:rgba(255,255,255,0.7); text-decoration:none; padding:8px 16px; border-radius:var(--r-sm); font-size:13px; font-weight:600; }
.nav-link:hover { background:rgba(255,255,255,0.1); color:#fff; }
.nav-link.active { background:rgba(255,255,255,0.2); color:#fff; }
.user-menu { display:flex; align-items:center; gap:10px; }
.user-avatar { width:36px; height:36px; border-radius:50%; background:var(--berry); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; }
.user-name { color:#fff; font-weight:600; font-size:13px; }
.hero-strip { background: linear-gradient(135deg, #5C3D20 0%, #3B2A1A 100%); padding: 1.5rem 2.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom: 3px solid var(--soil); }
.hero-title { font-family:'Playfair Display',serif; font-size:28px; font-weight:700; color:#fff; }
.hero-title span { color:var(--sage); font-size:14px; font-weight:400; display:block; font-family:'Nunito',sans-serif; }
.layout { display:flex; min-height:calc(100vh - 66px - 70px); }
.sidebar { width:220px; flex-shrink:0; background:var(--warm-w); border-right: 2px solid var(--border); padding:1.5rem 0.75rem; position:sticky; top:66px; height:calc(100vh - 66px); overflow-y:auto; }
.sidebar-section { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-faint); padding:0 10px; margin:1.25rem 0 6px; }
.sidebar-section:first-child { margin-top:0; }
.nav-btn { display:flex; align-items:center; gap:10px; width:100%; border:none; background:none; padding:9px 12px; border-radius:var(--r-sm); cursor:pointer; font-family:'Nunito',sans-serif; font-size:13px; font-weight:600; color:var(--text-muted); text-align:left; transition:all 0.15s; }
.nav-btn i { font-size:18px; }
.nav-btn:hover { background:var(--parch); color:var(--fern); }
.nav-btn.active { background:var(--mist); color:var(--moss); }
.nav-btn .badge { margin-left:auto; font-size:10px; font-weight:700; background:var(--berry); color:#fff; padding:2px 7px; border-radius:20px; }
.main { flex:1; padding:2rem 2.5rem; min-width:0; }
.page { display:none; }
.page.active { display:block; }
.stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:1.75rem; }
.stat { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-md); padding:1.1rem 1.25rem; box-shadow:var(--shadow-sm); position:relative; overflow:hidden; }
.stat::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--leaf); }
.stat.danger::after { background:var(--berry); }
.stat.warn::after { background:var(--sun); }
.stat.sky::after { background:var(--sky); }
.stat .s-lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-faint); margin-bottom:8px; }
.stat .s-val { font-size:28px; font-weight:700; color:var(--text-dark); line-height:1; }
.stat.danger .s-val { color:var(--berry); }
.stat.warn .s-val { color:var(--dusk); }
.card { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-lg); padding:1.5rem; box-shadow:var(--shadow-sm); margin-bottom:1.25rem; }
.card-head { display:flex; align-items:center; gap:10px; margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border); }
.card-head h3 { font-family:'Playfair Display',serif; font-size:17px; font-weight:600; color:var(--text-dark); }
.card-head i { font-size:20px; color:var(--fern); }
.card-head .head-action { margin-left:auto; }
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
.g3 { display:grid; grid-template-columns:1fr 1fr 1fr; gap:1rem; }
.pill { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:4px 11px; border-radius:30px; }
.p-critical { background:#FEE2E2; color:#7F1D1D; }
.p-high { background:#FEF3C7; color:#78350F; }
.p-low { background:var(--mist); color:var(--moss); }
.p-open { background:#FEE2E2; color:#7F1D1D; }
.p-inprog { background:#FEF3C7; color:#78350F; }
.p-resolved { background:var(--mist); color:var(--moss); }
.alert { display:flex; gap:12px; padding:14px 16px; border-radius:var(--r-md); margin-bottom:10px; border-left:4px solid; }
.alert.danger { background:#FFF5F5; border-color:var(--berry); }
.alert.warning { background:#FFFBEB; border-color:var(--sun); }
.alert.info { background:#F0F8FF; border-color:var(--sky); }
.alert i { font-size:20px; flex-shrink:0; }
.alert.danger i { color:var(--berry); }
.alert.warning i { color:var(--dusk); }
.alert.info i { color:var(--sky); }
.alert-title { font-size:13px; font-weight:700; color:var(--text-dark); }
.alert-body { font-size:12px; color:var(--text-muted); margin-top:3px; }
.btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:var(--r-md); font-family:'Nunito',sans-serif; font-size:13px; font-weight:700; cursor:pointer; border:none; transition:all 0.15s; }
.btn-primary { background:var(--fern); color:#fff; }
.btn-primary:hover { background:var(--moss); }
.btn-danger { background:var(--berry); color:#fff; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:12px; }
.form-label { font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; }
.form-control { font-family:'Nunito',sans-serif; font-size:13px; padding:10px 14px; border-radius:var(--r-md); border:1.5px solid var(--border-md); background:var(--cream); color:var(--text-dark); }
textarea.form-control { resize:none; height:80px; }
.tbl-head { display:grid; padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-faint); background:var(--parch); border-radius:var(--r-sm); margin-bottom:4px; }
.tbl-row { display:grid; padding:12px 12px; align-items:center; border-radius:var(--r-sm); border-bottom:1px solid var(--border); gap:12px; }
.tbl-row:last-child { border-bottom:none; }
.tbl-row .name { font-weight:600; font-size:13px; color:var(--text-dark); }
.tbl-row .sub { font-size:11px; color:var(--text-muted); }
.acc-row { display:grid; grid-template-columns:70px 70px 1fr 70px; gap:10px; padding:10px 12px; align-items:center; border-radius:var(--r-sm); font-size:13px; border-bottom:1px solid var(--border); }
.acc-row:last-child { border-bottom:none; }
.acc-row.hdr { background:var(--parch); border-radius:var(--r-sm); font-size:10px; font-weight:700; text-transform:uppercase; color:var(--text-faint); margin-bottom:4px; border-bottom:none; }
.p-in { background:var(--mist); color:var(--moss); }
.p-out { background:var(--sky-lt); color:var(--sky); }
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-brand">
    <div class="brand-icon"><i class="ti ti-shield-check"></i></div>
    <div>
      <div class="brand-name">Come Garden Admin</div>
      <div class="brand-sub">Volunteer Management</div>
    </div>
  </div>
  <div class="topbar-right">
    <a href="{{ route('volunteer') }}" class="nav-link">Member View</a>
    <div class="user-menu">
      <div class="user-avatar">{{ substr(Auth::user()->name ?? 'A', 0, 2) }}</div>
      <span class="user-name">{{ Auth::user()->name ?? 'Admin' }}</span>
    </div>
  </div>
</header>

<div class="hero-strip">
  <div class="hero-title">
    <span>Admin Dashboard</span>
    {{ now()->format('F Y') }}
  </div>
</div>

<div class="layout">
  <nav class="sidebar">
    <div class="sidebar-section">Overview</div>
    <button class="nav-btn active" onclick="show('overview',this)"><i class="ti ti-dashboard"></i>Dashboard</button>

    <div class="sidebar-section">Management</div>
    <button class="nav-btn" onclick="show('shifts',this)"><i class="ti ti-calendar"></i>Shifts</button>
    <button class="nav-btn" onclick="show('alerts',this)"><i class="ti ti-broadcast"></i>Alerts <span class="badge">{{ $alertCount }}</span></button>

    <div class="sidebar-section">Monitoring</div>
    <button class="nav-btn" onclick="show('access',this)"><i class="ti ti-door"></i>Access Log</button>
    <button class="nav-btn" onclick="show('incidents',this)"><i class="ti ti-alert-circle"></i>Incidents <span class="badge">{{ $openIncidents }}</span></button>

    <div class="sidebar-section">Finance</div>
    <button class="nav-btn" onclick="show('proposals',this)"><i class="ti ti-receipt"></i>Fund Proposals</button>
    <button class="nav-btn" onclick="show('voting',this)"><i class="ti ti-chart-bar"></i>Fund Voting</button>
  </nav>

  <main class="main">
    <!-- OVERVIEW -->
    <div class="page active" id="page-overview">
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Total Members</div><div class="s-val">{{ $totalMembers }}</div></div>
        <div class="stat"><div class="s-lbl">Active Shifts</div><div class="s-val">{{ $activeShifts }}</div></div>
        <div class="stat warn"><div class="s-lbl">At Risk</div><div class="s-val">{{ $atRisk }}</div><div class="s-sub">members below requirement</div></div>
        <div class="stat danger"><div class="s-lbl">Open Incidents</div><div class="s-val">{{ $openIncidents }}</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-chart-bar"></i><h3>Quick Stats</h3></div>
          <div class="g3" style="text-align:center">
            <div><div class="s-val" style="font-size:36px">{{ $totalShifts }}</div><div class="sub">Total Shifts</div></div>
            <div><div class="s-val" style="font-size:36px">{{ $totalAssignments }}</div><div class="sub">Assignments</div></div>
            <div><div class="s-val" style="font-size:36px;color:var(--fern)">{{ $metRequirement }}</div><div class="sub">Met Requirement</div></div>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-alert-triangle"></i><h3>Recent Alerts</h3></div>
          @forelse($activeAlerts->take(3) as $alert)
          <div class="alert @if($alert['severity']=='critical') danger @elseif($alert['severity']=='warning') warning @else info @endif">
            <i class="ti @if($alert['severity']=='critical') ti-alert-triangle @else ti-info-circle @endif"></i>
            <div><div class="alert-title">{{ $alert['title'] }}</div><div class="alert-body">{{ $alert['created_at'] }}</div></div>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No active alerts</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- SHIFTS -->
    <div class="page" id="page-shifts">
      <div class="card">
        <div class="card-head">
          <i class="ti ti-calendar"></i>
          <h3>All Shifts</h3>
          <div class="head-action">
            <form method="POST" action="{{ route('admin.volunteer.shift.create') }}" style="display:flex;gap:8px">
              @csrf
              <input type="date" name="start_date" class="form-control" required style="width:150px">
              <input type="number" name="duration_days" class="form-control" placeholder="Days" required min="1" style="width:80px">
              <button type="submit" class="btn btn-primary">+ New Shift</button>
            </form>
          </div>
        </div>
        <div class="tbl-head" style="grid-template-columns:80px 100px 80px 80px 80px 1fr">
          <span>ID</span><span>Start</span><span>End</span><span>Heavy</span><span>Light</span><span>Status</span>
        </div>
        @forelse($shifts as $shift)
        <div class="tbl-row" style="grid-template-columns:80px 100px 80px 80px 80px 1fr">
          <span class="sub">#{{ $shift['id'] }}</span>
          <span class="sub">{{ $shift['start_date'] }}</span>
          <span class="sub">{{ $shift['end_date'] }}</span>
          <span class="sub">{{ $shift['heavy_count'] }}</span>
          <span class="sub">{{ $shift['light_count'] }}</span>
          <span class="pill @if($shift['status']=='active') p-resolved @else p-inprog @endif">{{ $shift['status'] }}</span>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:80px 100px 80px 80px 80px 1fr"><span class="sub" colspan="6">No shifts</span></div>
        @endforelse
      </div>
    </div>

    <!-- ALERTS -->
    <div class="page" id="page-alerts">
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-broadcast"></i><h3>Broadcast New Alert</h3></div>
          <form method="POST" action="{{ route('volunteer.alert.broadcast') }}">
            @csrf
            <div class="form-group">
              <div class="form-label">Severity</div>
              <select name="severity" class="form-control">
                <option value="critical">🔴 Critical - Emergency</option>
                <option value="warning">🟠 Warning</option>
                <option value="info">🟡 Info</option>
              </select>
            </div>
            <div class="form-group">
              <div class="form-label">Title</div>
              <input type="text" name="title" class="form-control" placeholder="Alert title" required>
            </div>
            <div class="form-group">
              <div class="form-label">Message</div>
              <textarea name="message" class="form-control" placeholder="Alert message..." required></textarea>
            </div>
            <button type="submit" class="btn btn-danger"><i class="ti ti-send"></i> Broadcast</button>
          </form>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-history"></i><h3>Active Alerts</h3></div>
          @forelse($activeAlerts as $alert)
          <div class="alert @if($alert['severity']=='critical') danger @else warning @endif">
            <i class="ti ti-alert-triangle"></i>
            <div>
              <div class="alert-title">{{ $alert['title'] }}</div>
              <div class="alert-body">{{ $alert['message'] }} · {{ $alert['created_at'] }}</div>
            </div>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No active alerts</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- ACCESS LOG -->
    <div class="page" id="page-access">
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Entries Today</div><div class="s-val">{{ $entriesToday }}</div></div>
        <div class="stat"><div class="s-lbl">Exits Today</div><div class="s-val">{{ $exitsToday }}</div></div>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-door"></i><h3>Recent Access Log</h3></div>
        <div class="acc-row hdr"><span>Time</span><span>Date</span><span>Member</span><span>Action</span></div>
        @forelse($recentAccess as $log)
        <div class="acc-row">
          <span class="sub">{{ $log['time'] }}</span>
          <span class="sub">{{ $log['date'] }}</span>
          <span class="name">{{ $log['member_name'] }}</span>
          <span class="pill @if($log['action']=='entry') p-in @else p-out @endif">{{ $log['action'] }}</span>
        </div>
        @empty
        <div class="acc-row"><span class="sub" colspan="4">No logs</span></div>
        @endforelse
      </div>
    </div>

    <!-- INCIDENTS -->
    <div class="page" id="page-incidents">
      <div class="stat-row">
        <div class="stat danger"><div class="s-lbl">Open</div><div class="s-val">{{ $openIncidents }}</div></div>
        <div class="stat warn"><div class="s-lbl">In Progress</div><div class="s-val">{{ $inProgressIncidents }}</div></div>
        <div class="stat danger"><div class="s-lbl">Critical</div><div class="s-val">{{ $criticalIncidents }}</div></div>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-alert-circle"></i><h3>All Incidents</h3></div>
        @forelse($incidents as $incident)
        <div class="tbl-row" style="grid-template-columns:1fr 100px 100px 100px">
          <div>
            <div class="name">{{ $incident['title'] }}</div>
            <div class="sub">{{ $incident['location'] }} · {{ $incident['reporter'] }}</div>
          </div>
          <span class="pill @if($incident['severity']=='critical' || $incident['severity']=='high') p-critical @elseif($incident['severity']=='medium') p-high @else p-low @endif">{{ $incident['severity'] }}</span>
          <span class="pill @if($incident['status']=='open') p-open @elseif($incident['status']=='in_progress') p-inprog @else p-resolved @endif">{{ $incident['status'] }}</span>
          <form method="POST" action="{{ route('volunteer.incident.update', $incident['id']) }}">
            @csrf
            <input type="hidden" name="status" value="resolved">
            <button type="submit" class="btn btn-primary" style="padding:4px 8px;font-size:11px">Resolve</button>
          </form>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:1fr"><span class="sub">No incidents</span></div>
        @endforelse
      </div>
    </div>

    <!-- PROPOSALS -->
    <div class="page" id="page-proposals">
      <div class="card">
        <div class="card-head"><i class="ti ti-podium"></i><h3>Fund Proposals</h3></div>
        @forelse($proposals as $proposal)
        <div class="tbl-row" style="grid-template-columns:1fr 80px 80px 80px">
          <div>
            <div class="name">{{ $proposal['title'] }}</div>
            <div class="sub">£{{ $proposal['estimated_cost'] }} · {{ $proposal['total'] }} votes</div>
          </div>
          <span class="pill @if($proposal['status']=='open') p-inprog @else p-resolved @endif">{{ $proposal['status'] }}</span>
          <span style="font-weight:700;color:var(--fern)">{{ $proposal['percentage'] }}%</span>
          @if($proposal['status']=='open')
          <form method="POST" action="{{ route('volunteer.proposals.close', $proposal['id']) }}">
            @csrf
            <button type="submit" class="btn btn-primary" style="padding:4px 8px;font-size:11px">Close</button>
          </form>
          @endif
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:1fr"><span class="sub">No proposals</span></div>
        @endforelse
      </div>
    </div>

    <!-- FUND VOTING -->
    <div class="page" id="page-voting">
      <div class="page-title">
        <div>
          <h2>Fund Voting Management</h2>
          <p>Create proposals, view detailed voting results, and manage communal fund decisions.</p>
        </div>
        <button class="btn btn-primary" onclick="document.getElementById('new-proposal-form').style.display='block'">
          <i class="ti ti-plus"></i> New Proposal
        </button>
      </div>

      <!-- Create Proposal Form -->
      <div id="new-proposal-form" style="display:none;background:var(--warm-w);border:1px solid var(--border);border-radius:var(--r-md);padding:1.5rem;margin-bottom:1.5rem">
        <h3 style="margin-bottom:1rem">Create New Proposal</h3>
        <form method="POST" action="{{ route('volunteer.proposals.create') }}">
          @csrf
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div class="form-group">
              <div class="form-label">Title</div>
              <input type="text" name="title" class="form-control" placeholder="e.g. New greenhouse" required>
            </div>
            <div class="form-group">
              <div class="form-label">Estimated Cost (£)</div>
              <input type="number" name="estimated_cost" class="form-control" placeholder="500" required>
            </div>
          </div>
          <div class="form-group">
            <div class="form-label">Description</div>
            <textarea name="description" class="form-control" placeholder="Describe the proposal..." required></textarea>
          </div>
          <div class="form-group">
            <div class="form-label">Voting Ends</div>
            <input type="date" name="voting_ends_at" class="form-control" required min="{{ now()->addDay()->format('Y-m-d') }}">
          </div>
          <button type="submit" class="btn btn-primary">Create Proposal</button>
          <button type="button" class="btn btn-ghost" onclick="document.getElementById('new-proposal-form').style.display='none'">Cancel</button>
        </form>
      </div>

      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Active</div><div class="s-val">{{ $proposals->where('status', 'open')->count() }}</div></div>
        <div class="stat sky"><div class="s-lbl">Total Votes</div><div class="s-val">{{ $totalVotesCast }}</div></div>
        <div class="stat"><div class="s-lbl">Closed</div><div class="s-val">{{ $proposals->where('status', 'closed')->count() }}</div></div>
      </div>

      <div class="card">
        <div class="card-head"><i class="ti ti-chart-bar"></i><h3>All Proposals with Vote Details</h3></div>
        @forelse($proposals as $proposal)
        <div style="padding:1rem;border-bottom:1px solid var(--border)">
          <div style="display:flex;justify-content:space-between;align-items:center">
            <div>
              <div class="name">{{ $proposal['title'] }}</div>
              <div class="sub">£{{ $proposal['estimated_cost'] }} · {{ $proposal['description'] }}</div>
            </div>
            <div style="text-align:right">
              <span class="pill @if($proposal['status']=='open') p-inprog @else p-resolved @endif">{{ $proposal['status'] }}</span>
              <div style="margin-top:4px;font-size:12px;color:var(--text-muted)">Ends {{ $proposal['voting_ends_at']->format('d M Y') }}</div>
            </div>
          </div>
          
          <!-- Vote Results -->
          <div style="margin-top:1rem;display:grid;grid-template-columns:1fr 1fr;gap:1rem">
            <div style="background:var(--parch);padding:0.75rem;border-radius:var(--r-sm)">
              <div style="font-size:11px;color:var(--text-muted)">YES</div>
              <div style="font-size:20px;font-weight:700;color:var(--fern)">{{ $proposal['yes'] }}</div>
              <div style="font-size:11px">{{ $proposal['percentage'] }}%</div>
            </div>
            <div style="background:var(--parch);padding:0.75rem;border-radius:var(--r-sm)">
              <div style="font-size:11px;color:var(--text-muted)">NO</div>
              <div style="font-size:20px;font-weight:700;color:var(--berry)">{{ $proposal['no'] }}</div>
              <div style="font-size:11px">{{ 100 - $proposal['percentage'] }}%</div>
            </div>
          </div>

          @if($proposal['status']=='open')
          <form method="POST" action="{{ route('volunteer.proposals.close', $proposal['id']) }}" style="margin-top:1rem">
            @csrf
            <button type="submit" class="btn btn-primary"><i class="ti ti-lock"></i> Close Voting</button>
          </form>
          @endif
        </div>
        @empty
        <div style="padding:2rem;text-align:center;color:var(--text-muted)">No proposals yet</div>
        @endforelse
      </div>
    </div>
  </main>
</div>

<script>
function show(id, btn) {
  document.querySelectorAll('.page').forEach(p => p.classList.remove('active'));
  document.querySelectorAll('.nav-btn').forEach(b => b.classList.remove('active'));
  document.getElementById('page-' + id).classList.add('active');
  btn.classList.add('active');
}
</script>
</body>
</html>