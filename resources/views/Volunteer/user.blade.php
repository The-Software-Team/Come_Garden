<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>My Volunteer Portal — Come Garden</title>
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Nunito:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@tabler/icons-webfont@latest/dist/tabler-icons.min.css">
<style>
:root { --soil: #3B2A1A; --bark: #5C3D20; --moss: #2D5016; --fern: #3E7021; --leaf: #5A9A2E; --sage: #8CB87A; --mist: #D4E8C2; --cream: #F7F3EC; --parch: #EDE7DA; --warm-w: #FDFAF4; --sun: #E8A020; --dusk: #C46820; --berry: #8B2335; --sky: #2E6B8A; --sky-lt: #D0E8F2; --text-dark: #1E1409; --text-body: #3D2E1C; --text-muted: #7A6A54; --text-faint: #B0A090; --border: rgba(90,60,20,0.12); --border-md: rgba(90,60,20,0.22); --r-sm: 8px; --r-md: 14px; --r-lg: 20px; --shadow-sm: 0 2px 8px rgba(59,42,26,0.08); }
*{box-sizing:border-box;margin:0;padding:0}
body { font-family: 'Nunito', sans-serif; background: var(--cream); color: var(--text-body); min-height: 100vh; }
.topbar { background: var(--moss); background-image: radial-gradient(ellipse at 20% 50%, rgba(90,154,46,0.3) 0%, transparent 60%), radial-gradient(ellipse at 80% 30%, rgba(45,80,22,0.4) 0%, transparent 50%); padding: 0 2.5rem; height: 66px; display: flex; align-items: center; justify-content: space-between; position: sticky; top:0; z-index:200; border-bottom: 2px solid rgba(255,255,255,0.08); }
.topbar-brand { display:flex; align-items:center; gap:14px; }
.brand-icon { width:42px; height:42px; border-radius:12px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; }
.brand-name { font-family:'Playfair Display',serif; font-size:18px; font-weight:600; color:#fff; letter-spacing:0.01em; }
.brand-sub { font-size:12px; color:rgba(255,255,255,0.6); margin-top:1px; }
.topbar-right { display:flex; align-items:center; gap:12px; }
.user-menu { display:flex; align-items:center; gap:10px; }
.user-avatar { width:36px; height:36px; border-radius:50%; background:var(--leaf); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; }
.user-name { color:#fff; font-weight:600; font-size:13px; }
.hero-strip { background: linear-gradient(135deg, #2D5016 0%, #3E7021 100%); padding: 1.5rem 2.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom: 3px solid var(--bark); }
.hero-title { font-family:'Playfair Display',serif; font-size:28px; font-weight:700; color:#fff; }
.hero-title span { color:var(--sage); font-size:14px; font-weight:400; display:block; }
.layout { display:flex; min-height:calc(100vh - 66px - 70px); }
.sidebar { width:200px; flex-shrink:0; background:var(--warm-w); border-right: 2px solid var(--border); padding:1.5rem 0.75rem; position:sticky; top:66px; height:calc(100vh - 66px); overflow-y:auto; }
.sidebar-section { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-faint); padding:0 10px; margin:1.25rem 0 6px; }
.sidebar-section:first-child { margin-top:0; }
.nav-btn { display:flex; align-items:center; gap:10px; width:100%; border:none; background:none; padding:9px 12px; border-radius:var(--r-sm); cursor:pointer; font-family:'Nunito',sans-serif; font-size:13px; font-weight:600; color:var(--text-muted); text-align:left; transition:all 0.15s; }
.nav-btn i { font-size:18px; }
.nav-btn:hover { background:var(--parch); color:var(--fern); }
.nav-btn.active { background:var(--mist); color:var(--moss); }
.nav-btn .badge { margin-left:auto; font-size:10px; font-weight:700; background:var(--dusk); color:#fff; padding:2px 7px; border-radius:20px; }
.main { flex:1; padding:2rem 2.5rem; min-width:0; }
.page { display:none; }
.page.active { display:block; }
.stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:1.75rem; }
.stat { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-md); padding:1.1rem 1.25rem; box-shadow:var(--shadow-sm); position:relative; overflow:hidden; }
.stat::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--leaf); }
.stat .s-lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-faint); margin-bottom:8px; }
.stat .s-val { font-size:28px; font-weight:700; color:var(--text-dark); line-height:1; }
.stat .s-sub { font-size:12px; color:var(--text-muted); margin-top:5px; }
.card { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-lg); padding:1.5rem; box-shadow:var(--shadow-sm); margin-bottom:1.25rem; }
.card-head { display:flex; align-items:center; gap:10px; margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border); }
.card-head h3 { font-family:'Playfair Display',serif; font-size:17px; font-weight:600; color:var(--text-dark); }
.card-head i { font-size:20px; color:var(--fern); }
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
.pill { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:4px 11px; border-radius:30px; }
.p-assigned { background:var(--sky-lt); color:var(--sky); }
.p-completed { background:var(--mist); color:var(--moss); }
.p-open { background:#FEE2E2; color:#7F1D1D; }
.p-pending { background:#FEF3C7; color:#78350F; }
.p-met { background:var(--mist); color:var(--moss); }
.p-in { background:var(--mist); color:var(--moss); }
.p-out { background:var(--sky-lt); color:var(--sky); }
.tbl-head { display:grid; padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-faint); background:var(--parch); border-radius:var(--r-sm); margin-bottom:4px; }
.tbl-row { display:grid; padding:12px 12px; align-items:center; border-radius:var(--r-sm); border-bottom:1px solid var(--border); gap:12px; }
.tbl-row:last-child { border-bottom:none; }
.tbl-row .name { font-weight:600; font-size:13px; color:var(--text-dark); }
.tbl-row .sub { font-size:11px; color:var(--text-muted); }
.btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:var(--r-md); font-family:'Nunito',sans-serif; font-size:13px; font-weight:700; cursor:pointer; border:none; transition:all 0.15s; }
.btn-primary { background:var(--fern); color:#fff; }
.btn-primary:hover { background:var(--moss); }
.btn-swap { background:var(--sky-lt); color:var(--sky); border:1.5px solid var(--sky); padding:5px 12px; border-radius:var(--r-sm); font-size:11px; font-weight:700; }
.btn-ghost { background:transparent; color:var(--text-muted); border:1.5px solid var(--border-md); }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:12px; }
.form-label { font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; }
.form-control { font-family:'Nunito',sans-serif; font-size:13px; padding:10px 14px; border-radius:var(--r-md); border:1.5px solid var(--border-md); background:var(--cream); color:var(--text-dark); }
textarea.form-control { resize:none; height:80px; }
.prog-wrap { margin-top:10px; }
.prog-bg { height:7px; background:var(--parch); border-radius:4px; overflow:hidden; border:1px solid var(--border); }
.prog-fill { height:100%; border-radius:4px; background:var(--leaf); }
.prog-fill.amber { background:var(--dusk); }
.pair-card { background:var(--cream); border:1.5px solid var(--border); border-radius:var(--r-md); padding:14px 16px; display:flex; align-items:center; gap:12px; }
.pair-info { flex:1; }
.pair-info .pname { font-size:13px; font-weight:700; color:var(--text-dark); }
.pair-tags { display:flex; flex-wrap:wrap; gap:4px; margin-top:5px; }
.tag { font-size:10px; font-weight:600; background:var(--warm-w); border:1px solid var(--border-md); color:var(--text-muted); padding:2px 8px; border-radius:20px; }
</style>
</head>
<body>

<header class="topbar">
  <div class="topbar-brand">
    <div class="brand-icon"><i class="ti ti-plant-2"></i></div>
    <div>
      <div class="brand-name">Come Garden</div>
      <div class="brand-sub">My Volunteer Portal</div>
    </div>
  </div>
  <div class="topbar-right">
    <div class="user-menu">
      <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</div>
      <span class="user-name">{{ Auth::user()->name ?? 'Member' }}</span>
    </div>
  </div>
</header>

<div class="hero-strip">
  <div class="hero-title">
    <span>My Dashboard</span>
    {{ now()->format('F Y') }}
  </div>
</div>

<div class="layout">
  <nav class="sidebar">
    <div class="sidebar-section">My Work</div>
    <button class="nav-btn active" onclick="show('shifts',this)"><i class="ti ti-calendar"></i>My Shifts</button>
    <button class="nav-btn" onclick="show('hours',this)"><i class="ti ti-clock"></i>Service Hours</button>

    <div class="sidebar-section">Community</div>
    <button class="nav-btn" onclick="show('votes',this)"><i class="ti ti-podium"></i>Fund Voting</button>
    <button class="nav-btn" onclick="show('mentor',this)"><i class="ti ti-users"></i>My Mentor</button>

    <div class="sidebar-section">Safety</div>
    <button class="nav-btn" onclick="show('incidents',this)"><i class="ti ti-alert-circle"></i>My Incidents</button>
    <button class="nav-btn" onclick="show('swaps',this)"><i class="ti ti-arrows-exchange"></i>My Swaps</button>
  </nav>

  <main class="main">
    <!-- MY SHIFTS -->
    <div class="page active" id="page-shifts">
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Total Hours</div><div class="s-val">{{ $totalHours }}</div><div class="s-sub">all time</div></div>
        <div class="stat"><div class="s-lbl">Shifts Done</div><div class="s-val">{{ $completedCount }}</div><div class="s-sub">completed</div></div>
        <div class="stat"><div class="s-lbl">This Month</div><div class="s-val">{{ $thisMonthHours }}</div><div class="s-sub">hours</div></div>
        <div class="stat"><div class="s-lbl">Required</div><div class="s-val">{{ $requiredHours }}</div><div class="s-sub">hours/month</div></div>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-calendar"></i><h3>My Upcoming Shifts</h3></div>
        <div class="tbl-head" style="grid-template-columns:70px 1fr 80px 80px 80px">
          <span>Date</span><span>Task</span><span>Role</span><span>Status</span><span>Action</span>
        </div>
        @forelse($myAssignments as $a)
        <div class="tbl-row" style="grid-template-columns:70px 1fr 80px 80px 80px">
          <span class="sub">{{ $a['date'] ?? 'TBD' }}</span>
          <span class="name">{{ $a['task'] }}</span>
          <span class="pill @if($a['role']=='heavy') p-assigned @else p-completed @endif">{{ $a['role'] }}</span>
          <span class="pill @if($a['status']=='assigned') p-assigned @else p-completed @endif">{{ $a['status'] }}</span>
          <button class="btn-swap">Swap</button>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:70px 1fr 80px 80px 80px"><span class="sub" colspan="5">No upcoming shifts</span></div>
        @endforelse
      </div>
    </div>

    <!-- SERVICE HOURS -->
    <div class="page" id="page-hours">
      <div class="card">
        <div class="card-head"><i class="ti ti-clock"></i><h3>My Service Ledger</h3></div>
        <div style="text-align:center;padding:30px">
          <div style="font-family:'Playfair Display',serif;font-size:64px;font-weight:700;color:var(--text-dark)">{{ $totalHours }}</div>
          <div class="sub">total hours logged</div>
          <div class="prog-wrap" style="max-width:400px;margin:30px auto">
            <div class="prog-bg"><div class="prog-fill @if($totalHours < $requiredHours) amber @endif" style="width:{{ min(($totalHours / $requiredHours) * 100, 100) }}%"></div></div>
            <div style="display:flex;justify-content:space-between;margin-top:8px;font-size:12px;color:var(--text-muted)">
              <span>Progress</span><span>{{ $totalHours }}/{{ $requiredHours }} hrs</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- FUND VOTING -->
    <div class="page" id="page-votes">
      <div class="card">
        <div class="card-head"><i class="ti ti-podium"></i><h3>Fund Proposals</h3></div>
        <div class="tbl-head" style="grid-template-columns:1fr 80px 80px">
          <span>Proposal</span><span>Cost</span><span>Vote</span>
        </div>
        <div class="tbl-row" style="grid-template-columns:1fr 80px 80px">
          <span class="name">Build a new greenhouse</span>
          <span class="sub">£1,800</span>
          <button class="btn btn-primary" style="padding:4px 12px;font-size:11px">Vote</button>
        </div>
        <div class="tbl-row" style="grid-template-columns:1fr 80px 80px">
          <span class="name">Install beehives (×3)</span>
          <span class="sub">£900</span>
          <button class="btn btn-ghost" style="padding:4px 12px;font-size:11px">Vote</button>
        </div>
      </div>
    </div>

    <!-- MENTOR -->
    <div class="page" id="page-mentor">
      <div class="card">
        <div class="card-head"><i class="ti ti-users"></i><h3>My Mentor</h3></div>
        @if($myMentorData)
        <div class="pair-card">
          <div class="pair-info">
            <div class="pname">{{ $myMentorData['name'] }}</div>
            <div class="pair-tags">
              @foreach($myMentorData['interests'] as $interest)
              <span class="tag">{{ $interest }}</span>
              @endforeach
            </div>
          </div>
        </div>
        @else
        <div style="padding:30px;text-align:center">
          <p style="color:var(--text-muted);margin-bottom:16px">You don't have a mentor yet.</p>
          <form method="POST" action="{{ route('volunteer.mentor.pair') }}">
            @csrf
            <button type="submit" class="btn btn-primary">Request a Mentor</button>
          </form>
        </div>
        @endif
      </div>
    </div>

    <!-- INCIDENTS -->
    <div class="page" id="page-incidents">
      <div class="card">
        <div class="card-head"><i class="ti ti-alert-circle"></i><h3>Report New Incident</h3></div>
        <form method="POST" action="{{ route('volunteer.incident.report') }}">
          @csrf
          <div class="form-group">
            <div class="form-label">Title</div>
            <input type="text" name="title" class="form-control" placeholder="What's the hazard?" required>
          </div>
          <div class="form-group">
            <div class="form-label">Location</div>
            <input type="text" name="location" class="form-control" placeholder="Where is it?" required>
          </div>
          <div class="form-group">
            <div class="form-label">Description</div>
            <textarea name="description" class="form-control" placeholder="Describe the incident..." required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Submit Report</button>
        </form>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-history"></i><h3>My Reported Incidents</h3></div>
        @forelse($myIncidents as $i)
        <div class="tbl-row" style="grid-template-columns:1fr 80px 80px">
          <div><div class="name">{{ $i['title'] }}</div><div class="sub">{{ $i['location'] }} · {{ $i['created_at'] }}</div></div>
          <span class="pill @if($i['severity']=='critical') p-open @else p-pending @endif">{{ $i['severity'] }}</span>
          <span class="pill p-completed">{{ $i['status'] }}</span>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:1fr"><span class="sub">No incidents reported</span></div>
        @endforelse
      </div>
    </div>

    <!-- SWAPS -->
    <div class="page" id="page-swaps">
      <div class="card">
        <div class="card-head"><i class="ti ti-arrows-exchange"></i><h3>My Swap Requests</h3></div>
        @forelse($mySwapRequests as $swap)
        <div class="tbl-row" style="grid-template-columns:1fr 80px">
          <div><div class="name">Shift #{{ $swap->assignment_id }}</div><div class="sub">Requested {{ $swap->created_at->diffForHumans() }}</div></div>
          <span class="pill p-pending">{{ $swap->status }}</span>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:1fr"><span class="sub">No swap requests</span></div>
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