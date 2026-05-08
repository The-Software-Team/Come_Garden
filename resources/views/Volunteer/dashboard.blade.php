@use('Illuminate\Support\Str')
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8"/>
<meta name="viewport" content="width=device-width,initial-scale=1.0"/>
<title>Volunteer &amp; Community Operations — Come Garden</title>
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
  --r-sm: 8px; --r-md: 14px; --r-lg: 20px; --r-xl: 28px;
  --shadow-sm: 0 2px 8px rgba(59,42,26,0.08);
  --shadow-md: 0 6px 24px rgba(59,42,26,0.12);
}
*{box-sizing:border-box;margin:0;padding:0}
body { font-family: 'Nunito', sans-serif; background: var(--cream); color: var(--text-body); min-height: 100vh; }
.topbar { background: var(--moss); background-image: radial-gradient(ellipse at 20% 50%, rgba(90,154,46,0.3) 0%, transparent 60%), radial-gradient(ellipse at 80% 30%, rgba(45,80,22,0.4) 0%, transparent 50%); padding: 0 2.5rem; height: 66px; display: flex; align-items: center; justify-content: space-between; position: sticky; top:0; z-index:200; border-bottom: 2px solid rgba(255,255,255,0.08); }
.topbar-brand { display:flex; align-items:center; gap:14px; }
.brand-icon { width:42px; height:42px; border-radius:12px; background: rgba(255,255,255,0.15); border: 1px solid rgba(255,255,255,0.25); display:flex; align-items:center; justify-content:center; font-size:22px; color:#fff; }
.brand-name { font-family:'Playfair Display',serif; font-size:18px; font-weight:600; color:#fff; letter-spacing:0.01em; }
.brand-sub { font-size:12px; color:rgba(255,255,255,0.6); margin-top:1px; }
.topbar-right { display:flex; align-items:center; gap:12px; }
.weather-chip { display:flex; align-items:center; gap:7px; padding:7px 14px; background:rgba(255,255,255,0.12); border:1px solid rgba(255,255,255,0.2); border-radius:30px; font-size:13px; color:#fff; font-weight:600; }
.weather-chip i { font-size:18px; color:#FAC775; }
.status-dot { display:flex; align-items:center; gap:7px; padding:7px 14px; background:rgba(90,154,46,0.25); border:1px solid rgba(90,154,46,0.4); border-radius:30px; font-size:12px; color:var(--mist); font-weight:600; }
.status-dot::before { content:''; width:7px; height:7px; border-radius:50%; background:#8CB87A; box-shadow:0 0 0 2px rgba(140,184,122,0.4); }
.hero-strip { background: linear-gradient(135deg, #2D5016 0%, #3E7021 40%, #5C3D20 100%); padding: 2rem 2.5rem; display:flex; align-items:center; justify-content:space-between; border-bottom: 3px solid var(--bark); position:relative; overflow:hidden; }
.hero-strip::before { content:''; position:absolute; inset:0; background-image: radial-gradient(circle at 10% 80%, rgba(212,232,194,0.08) 0%, transparent 40%), radial-gradient(circle at 90% 20%, rgba(139,184,122,0.1) 0%, transparent 40%); }
.hero-month { font-family:'Playfair Display',serif; font-size:36px; font-weight:700; color:#fff; position:relative; }
.hero-month span { color:var(--sage); font-size:18px; font-weight:400; display:block; font-family:'Nunito',sans-serif; margin-bottom:4px; }
.hero-kpis { display:flex; gap:1rem; position:relative; }
.kpi-card { background:rgba(255,255,255,0.1); border:1px solid rgba(255,255,255,0.15); border-radius:var(--r-md); padding:14px 20px; text-align:center; min-width:90px; backdrop-filter:blur(4px); }
.kpi-val { font-size:28px; font-weight:700; color:#fff; line-height:1; }
.kpi-val.warn { color:#FAC775; }
.kpi-val.danger { color:#F7C1C1; }
.kpi-lbl { font-size:11px; color:rgba(255,255,255,0.6); margin-top:4px; font-weight:600; text-transform:uppercase; letter-spacing:0.05em; }
.layout { display:flex; min-height:calc(100vh - 66px - 90px); }
.sidebar { width:230px; flex-shrink:0; background:var(--warm-w); border-right: 2px solid var(--border); padding:1.5rem 0.75rem; position:sticky; top:66px; height:calc(100vh - 66px); overflow-y:auto; }
.sidebar-section { font-size:10px; font-weight:700; letter-spacing:0.1em; text-transform:uppercase; color:var(--text-faint); padding:0 10px; margin:1.25rem 0 6px; }
.sidebar-section:first-child { margin-top:0; }
.nav-btn { display:flex; align-items:center; gap:10px; width:100%; border:none; background:none; padding:9px 12px; border-radius:var(--r-sm); cursor:pointer; font-family:'Nunito',sans-serif; font-size:13px; font-weight:600; color:var(--text-muted); text-align:left; transition:all 0.15s; }
.nav-btn i { font-size:18px; }
.nav-btn:hover { background:var(--parch); color:var(--fern); }
.nav-btn.active { background:var(--mist); color:var(--moss); }
.nav-btn .badge { margin-left:auto; font-size:10px; font-weight:700; background:var(--dusk); color:#fff; padding:2px 7px; border-radius:20px; }
.nav-btn .badge.ok { background:var(--leaf); }
.main { flex:1; padding:2rem 2.5rem; min-width:0; }
.page { display:none; }
.page.active { display:block; }
.page-title { display:flex; align-items:flex-start; justify-content:space-between; margin-bottom:1.75rem; padding-bottom:1.25rem; border-bottom:2px solid var(--border); }
.page-title h2 { font-family:'Playfair Display',serif; font-size:26px; font-weight:600; color:var(--text-dark); }
.page-title p { font-size:13px; color:var(--text-muted); margin-top:5px; max-width:520px; }
.fn-badge { background:var(--moss); color:var(--mist); font-size:11px; font-weight:700; padding:5px 14px; border-radius:30px; white-space:nowrap; flex-shrink:0; margin-top:4px; }
.stat-row { display:grid; grid-template-columns:repeat(4,1fr); gap:14px; margin-bottom:1.75rem; }
.stat { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-md); padding:1.1rem 1.25rem; box-shadow:var(--shadow-sm); position:relative; overflow:hidden; }
.stat::after { content:''; position:absolute; top:0; left:0; right:0; height:3px; background:var(--leaf); }
.stat.warn::after { background:var(--sun); }
.stat.danger::after { background:var(--berry); }
.stat.sky::after { background:var(--sky); }
.stat .s-lbl { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-faint); margin-bottom:8px; }
.stat .s-val { font-size:28px; font-weight:700; color:var(--text-dark); line-height:1; }
.stat .s-sub { font-size:12px; color:var(--text-muted); margin-top:5px; }
.stat.warn .s-val { color:var(--dusk); }
.stat.danger .s-val { color:var(--berry); }
.stat.sky .s-val { color:var(--sky); }
.card { background:var(--warm-w); border:1.5px solid var(--border); border-radius:var(--r-lg); padding:1.5rem; box-shadow:var(--shadow-sm); margin-bottom:1.25rem; }
.card-head { display:flex; align-items:center; gap:10px; margin-bottom:1.25rem; padding-bottom:1rem; border-bottom:1px solid var(--border); }
.card-head h3 { font-family:'Playfair Display',serif; font-size:17px; font-weight:600; color:var(--text-dark); }
.card-head i { font-size:20px; color:var(--fern); }
.card-head .head-sub { font-size:12px; color:var(--text-muted); margin-top:2px; }
.card-head .head-action { margin-left:auto; }
.g2 { display:grid; grid-template-columns:1fr 1fr; gap:1.25rem; }
.pill { display:inline-flex; align-items:center; gap:4px; font-size:11px; font-weight:700; padding:4px 11px; border-radius:30px; }
.p-hard    { background:#FEE2E2; color:#7F1D1D; }
.p-medium  { background:#FEF3C7; color:#78350F; }
.p-easy    { background:var(--mist); color:var(--moss); }
.p-heavy   { background:#FEE2E2; color:#7F1D1D; }
.p-light   { background:var(--mist); color:var(--moss); }
.p-admin   { background:var(--sky-lt); color:var(--sky); }
.p-met     { background:var(--mist); color:var(--moss); }
.p-prog    { background:#FEF3C7; color:#78350F; }
.p-risk    { background:#FEE2E2; color:#7F1D1D; }
.p-open    { background:#FEE2E2; color:#7F1D1D; }
.p-inprog  { background:#FEF3C7; color:#78350F; }
.p-done    { background:var(--mist); color:var(--moss); }
.p-master  { background:#EDE9FE; color:#4C1D95; }
.p-novice  { background:#ECFDF5; color:#065F46; }
.p-in      { background:var(--mist); color:var(--moss); }
.p-out     { background:var(--sky-lt); color:var(--sky); }
.tbl-wrap { overflow:visible; }
.tbl-head { display:grid; padding:8px 12px; font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-faint); background:var(--parch); border-radius:var(--r-sm); margin-bottom:4px; }
.tbl-row { display:grid; padding:12px 12px; align-items:center; border-radius:var(--r-sm); transition:background 0.12s; border-bottom:1px solid var(--border); gap:12px; }
.tbl-row:last-child { border-bottom:none; }
.tbl-row:hover { background:var(--cream); }
.tbl-row .name { font-weight:600; font-size:13px; color:var(--text-dark); }
.tbl-row .sub  { font-size:11px; color:var(--text-muted); }
.av { width:36px; height:36px; border-radius:50%; flex-shrink:0; display:flex; align-items:center; justify-content:center; font-size:12px; font-weight:700; border:2px solid #fff; box-shadow:0 1px 4px rgba(0,0,0,0.12); }
.av-g { background:var(--mist); color:var(--moss); }
.av-b { background:var(--sky-lt); color:var(--sky); }
.av-a { background:#FEF3C7; color:#78350F; }
.av-p { background:#FCE7F3; color:#831843; }
.av-t { background:#ECFDF5; color:#065F46; }
.av-r { background:#FEE2E2; color:#7F1D1D; }
.prog-wrap { margin-top:5px; }
.prog-lbl { display:flex; justify-content:space-between; font-size:11px; color:var(--text-muted); margin-bottom:4px; }
.prog-bg { height:7px; background:var(--parch); border-radius:4px; overflow:hidden; border:1px solid var(--border); }
.prog-fill { height:100%; border-radius:4px; background:var(--leaf); }
.prog-fill.amber { background:var(--dusk); }
.prog-fill.red   { background:var(--berry); }
.alert { display:flex; gap:12px; padding:14px 16px; border-radius:var(--r-md); margin-bottom:10px; border-left:4px solid; }
.alert:last-child { margin-bottom:0; }
.alert.danger { background:#FFF5F5; border-color:var(--berry); }
.alert.warning { background:#FFFBEB; border-color:var(--sun); }
.alert.info   { background:#F0F8FF; border-color:var(--sky); }
.alert.success{ background:#F0FFF4; border-color:var(--leaf); }
.alert i { font-size:20px; flex-shrink:0; margin-top:1px; }
.alert.danger i  { color:var(--berry); }
.alert.warning i { color:var(--dusk); }
.alert.info i    { color:var(--sky); }
.alert.success i { color:var(--fern); }
.alert-title { font-size:13px; font-weight:700; color:var(--text-dark); }
.alert-body  { font-size:12px; color:var(--text-muted); margin-top:3px; }
.vote-item { padding:12px 0; border-bottom:1px solid var(--border); }
.vote-item:last-child { border-bottom:none; }
.vote-row { display:flex; justify-content:space-between; align-items:center; margin-bottom:7px; }
.vote-name { font-size:13px; font-weight:600; color:var(--text-dark); }
.vote-pct { font-size:14px; font-weight:700; }
.vote-sub { font-size:11px; color:var(--text-muted); margin-top:3px; }
.vote-bg { height:10px; background:var(--parch); border-radius:5px; overflow:hidden; border:1px solid var(--border); }
.vote-fill { height:100%; border-radius:5px; }
.btn { display:inline-flex; align-items:center; gap:7px; padding:9px 18px; border-radius:var(--r-md); font-family:'Nunito',sans-serif; font-size:13px; font-weight:700; cursor:pointer; border:none; transition:all 0.15s; }
.btn-primary { background:var(--fern); color:#fff; }
.btn-primary:hover { background:var(--moss); }
.btn-ghost { background:transparent; color:var(--text-muted); border:1.5px solid var(--border-md); }
.btn-ghost:hover { background:var(--parch); color:var(--text-dark); }
.btn-swap { background:var(--sky-lt); color:var(--sky); border:1.5px solid var(--sky); padding:5px 12px; border-radius:var(--r-sm); font-size:11px; font-weight:700; cursor:pointer; }
.btn-swap:hover { background:var(--sky); color:#fff; }
.btn-danger { background:var(--berry); color:#fff; }
.form-group { display:flex; flex-direction:column; gap:5px; margin-bottom:12px; }
.form-label { font-size:12px; font-weight:700; color:var(--text-muted); text-transform:uppercase; letter-spacing:0.05em; }
.form-control { font-family:'Nunito',sans-serif; font-size:13px; padding:10px 14px; border-radius:var(--r-md); border:1.5px solid var(--border-md); background:var(--cream); color:var(--text-dark); transition:border 0.15s; }
.form-control:focus { outline:none; border-color:var(--fern); }
textarea.form-control { resize:none; height:80px; }
.load-box { background:var(--parch); border:1.5px solid var(--border); border-radius:var(--r-md); padding:1.25rem; text-align:center; }
.load-num { font-family:'Playfair Display',serif; font-size:42px; font-weight:700; line-height:1; }
.load-num.green { color:var(--fern); }
.load-num.red   { color:var(--berry); }
.load-sub { font-size:12px; color:var(--text-muted); margin:6px 0 12px; }
.pair-card { background:var(--cream); border:1.5px solid var(--border); border-radius:var(--r-md); padding:14px 16px; margin-bottom:10px; display:flex; align-items:center; gap:12px; }
.pair-card:last-child { margin-bottom:0; }
.pair-info { flex:1; }
.pair-info .pname { font-size:13px; font-weight:700; color:var(--text-dark); }
.pair-tags { display:flex; flex-wrap:wrap; gap:4px; margin-top:5px; }
.tag { font-size:10px; font-weight:600; background:var(--warm-w); border:1px solid var(--border-md); color:var(--text-muted); padding:2px 8px; border-radius:20px; }
.pair-arrow { font-size:28px; color:var(--text-faint); padding:0 4px; font-weight:300; }
.match-badge { background:var(--mist); color:var(--moss); font-size:11px; font-weight:700; padding:4px 10px; border-radius:20px; white-space:nowrap; flex-shrink:0; border:1.5px solid var(--sage); }
.incident { display:flex; gap:14px; padding:14px 0; border-bottom:1px solid var(--border); align-items:flex-start; }
.incident:last-child { border-bottom:none; }
.sev-icon { width:38px; height:38px; border-radius:50%; display:flex; align-items:center; justify-content:center; flex-shrink:0; font-size:18px; }
.sev-h { background:#FEE2E2; } .sev-h i { color:var(--berry); }
.sev-m { background:#FEF3C7; } .sev-m i { color:var(--dusk); }
.sev-l { background:var(--mist); } .sev-l i { color:var(--fern); }
.inc-title { font-size:13px; font-weight:700; color:var(--text-dark); }
.inc-meta  { font-size:12px; color:var(--text-muted); margin-top:3px; }
.inc-actions { display:flex; gap:8px; margin-top:8px; align-items:center; }
.acc-row { display:grid; grid-template-columns:80px 1fr 90px 70px; gap:10px; padding:10px 12px; align-items:center; border-radius:var(--r-sm); font-size:13px; border-bottom:1px solid var(--border); }
.acc-row:last-child { border-bottom:none; }
.acc-row:hover { background:var(--cream); }
.acc-row.hdr { background:var(--parch); border-radius:var(--r-sm); font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-faint); margin-bottom:4px; border-bottom:none; }
.forecast { display:flex; gap:8px; }
.w-day { flex:1; text-align:center; padding:12px 6px; background:var(--parch); border-radius:var(--r-md); border:1.5px solid var(--border); }
.w-day.rain { background:#EFF6FF; border-color:#BFDBFE; }
.w-day .wday-name { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.06em; color:var(--text-faint); margin-bottom:7px; }
.w-day i { font-size:24px; }
.w-day .wday-temp { font-size:14px; font-weight:700; color:var(--text-dark); margin-top:6px; }
.w-day.rain .wday-name { color:var(--sky); }
.w-day.rain .wday-temp { color:var(--sky); }
.w-day .cancel-flag { font-size:9px; font-weight:700; color:var(--berry); background:#FEE2E2; padding:2px 6px; border-radius:10px; margin-top:5px; display:block; }
.thresh-row { display:flex; justify-content:space-between; align-items:center; padding:10px 14px; border-radius:var(--r-sm); margin-bottom:8px; font-size:13px; }
.thresh-row:last-child { margin-bottom:0; }
.thresh-row.cancel { background:#FFF5F5; color:var(--berry); border:1px solid #FECACA; }
.thresh-row.warn   { background:#FFFBEB; color:var(--dusk); border:1px solid #FDE68A; }
.thresh-label { font-weight:600; }
.thresh-action { font-weight:700; font-size:12px; }
::-webkit-scrollbar { width:5px; }
::-webkit-scrollbar-thumb { background:var(--sage); border-radius:4px; }
.divider { height:1px; background:var(--border); margin:1rem 0; }
.sec-lbl { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:0.08em; color:var(--text-faint); margin:1rem 0 8px; }
.sec-lbl:first-child { margin-top:0; }
.gate-box { background:var(--cream); border:1.5px solid var(--border-md); border-radius:var(--r-md); padding:14px 18px; }
.gate-name { font-size:11px; font-weight:700; text-transform:uppercase; letter-spacing:0.07em; color:var(--text-faint); margin-bottom:7px; }
.gate-code { font-size:22px; font-weight:700; letter-spacing:0.25em; color:var(--text-dark); font-variant-numeric:tabular-nums; }
.gate-rotate { font-size:11px; color:var(--text-muted); margin-top:5px; }
.user-menu { display:flex; align-items:center; gap:10px; }
.user-avatar { width:36px; height:36px; border-radius:50%; background:var(--leaf); color:#fff; display:flex; align-items:center; justify-content:center; font-weight:700; font-size:14px; }
.user-name { color:#fff; font-weight:600; font-size:13px; }
</style>
</head>
<body>

<!-- TOPBAR -->
<header class="topbar">
  <div class="topbar-brand">
    <div class="brand-icon"><i class="ti ti-plant-2"></i></div>
    <div>
      <div class="brand-name">Come Garden</div>
      <div class="brand-sub">Volunteer &amp; Community Operations</div>
    </div>
  </div>
  <div class="topbar-right">
    <div class="weather-chip"><i class="ti ti-sun"></i> 24°C · Sunny · Shifts confirmed</div>
    <div class="status-dot">All systems normal</div>
    <div class="user-menu">
      <div class="user-avatar">{{ substr(Auth::user()->name ?? 'U', 0, 2) }}</div>
      <span class="user-name">{{ Auth::user()->name ?? 'Member' }}</span>
    </div>
  </div>
</header>

<!-- HERO STRIP -->
<div class="hero-strip">
  <div class="hero-month">
    <span>Community Dashboard</span>
    {{ now()->format('F Y') }}
  </div>
  <div class="hero-kpis">
    <div class="kpi-card">
      <div class="kpi-val">{{ $teamMembers ?? 0 }}</div>
      <div class="kpi-lbl">Volunteers</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-val warn">{{ $mySwapCount ?? 0 }}</div>
      <div class="kpi-lbl">Swap requests</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-val danger">{{ $atRisk ?? 0 }}</div>
      <div class="kpi-lbl">At-risk members</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-val danger">{{ $openIncidents ?? 0 }}</div>
      <div class="kpi-lbl">Open incidents</div>
    </div>
    <div class="kpi-card">
      <div class="kpi-val">{{ $totalVotesCast ?? 0 }}</div>
      <div class="kpi-lbl">Fund votes cast</div>
    </div>
  </div>
</div>

<!-- MAIN LAYOUT -->
<div class="layout">

  <!-- SIDEBAR -->
  <nav class="sidebar">
    <div class="sidebar-section">Operations</div>
    <button class="nav-btn" data-tab="tasks" onclick="show('tasks',this)"><i class="ti ti-list-check"></i>Task Weighting</button>
    <button class="nav-btn" onclick="show('loadbalancer',this)"><i class="ti ti-scale"></i>Shift Load-Balancer</button>
    <button class="nav-btn" onclick="show('hours',this)"><i class="ti ti-clock"></i>Service Hours <span class="badge">{{ $thisMonthHours ?? 0 }}</span></button>
    <button class="nav-btn" onclick="show('shifts',this)"><i class="ti ti-calendar"></i>Shifts &amp; Swaps <span class="badge">{{ $myAssignments->count() }}</span></button>

    <div class="sidebar-section">Communication</div>
    <button class="nav-btn" onclick="show('broadcast',this)"><i class="ti ti-broadcast"></i>Emergency Alerts @if($alertCount > 0)<span class="badge ok">{{ $alertCount }}</span>@endif</button>
    <button class="nav-btn" onclick="show('voting',this)"><i class="ti ti-podium"></i>Fund Voting</button>

    <div class="sidebar-section">Security &amp; Safety</div>
    <button class="nav-btn" onclick="show('access',this)"><i class="ti ti-door"></i>Access Log</button>
    <button class="nav-btn" onclick="show('incidents',this)"><i class="ti ti-alert-circle"></i>Incidents <span class="badge">{{ $openIncidents ?? 0 }}</span></button>

    <div class="sidebar-section">Community</div>
    <button class="nav-btn" onclick="show('mentorship',this)"><i class="ti ti-users"></i>Mentorship</button>
    <button class="nav-btn" onclick="show('weather',this)"><i class="ti ti-cloud"></i>Weather &amp; Shifts</button>
  </nav>

  <main class="main">

    <!-- F23 — TASK WEIGHTING -->
    <div class="page" id="page-tasks">
      <div class="page-title">
        <div>
          <h2>Communal Task Weighting Logic</h2>
          <p>Assigns difficulty scores and contribution points to all communal garden tasks for fair volunteer accounting.</p>
          <span class="fn-badge">Function #23</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Total tasks</div><div class="s-val">{{ $totalTasks }}</div><div class="s-sub">registered tasks</div></div>
        <div class="stat danger"><div class="s-lbl">Hard tasks</div><div class="s-val">{{ $hardTasks }}</div><div class="s-sub">heavy labor required</div></div>
        <div class="stat warn"><div class="s-lbl">Medium tasks</div><div class="s-val">{{ $mediumTasks }}</div><div class="s-sub">moderate effort</div></div>
        <div class="stat"><div class="s-lbl">Easy tasks</div><div class="s-val">{{ $easyTasks }}</div><div class="s-sub">admin &amp; light</div></div>
      </div>
      <div class="card">
        <div class="card-head">
          <i class="ti ti-list-check"></i>
          <div><h3>Task Registry</h3><div class="head-sub">Points = difficulty × hours</div></div>
        </div>
        <div class="tbl-wrap">
          <div class="tbl-head" style="grid-template-columns:2fr 90px 60px 80px">
            <span>Task</span><span>Difficulty</span><span>Hours</span><span>Points</span>
          </div>
          @foreach($tasks as $task)
          <div class="tbl-row" style="grid-template-columns:2fr 90px 60px 80px">
            <span class="name">{{ $task['name'] }}</span>
            <span>
              @if($task['category'] == 'heavy')
              <span class="pill p-hard">Hard</span>
              @elseif($task['category'] == 'medium')
              <span class="pill p-medium">Medium</span>
              @else
              <span class="pill p-easy">Easy</span>
              @endif
            </span>
            <span>{{ $task['estimated_hours'] }} h</span>
            <span style="font-weight:700;@if($task['difficulty_score'] >= 6) color:var(--fern) @elseif($task['difficulty_score'] >= 3) color:var(--dusk) @else color:var(--text-muted) @endif">{{ $task['points'] }} pts</span>
          </div>
          @endforeach
        </div>
      </div>
    </div>

    <!-- F24 — LOAD BALANCER -->
    <div class="page" id="page-loadbalancer">
      <div class="page-title">
        <div>
          <h2>Volunteer Shift Load-Balancer</h2>
          <p>Ensures each communal workday has an optimal mix of heavy laborers and administrative volunteers for safe, effective operations.</p>
          <span class="fn-badge">Function #24</span>
        </div>
      </div>
      <div class="g2" style="margin-bottom:1.25rem">
        <div class="load-box">
          <div class="sec-lbl">Heavy laborers</div>
          <div class="load-num @if($totalHeavyAssigned >= 12) red @else green @endif">{{ $totalHeavyAssigned }} <span style="font-size:20px;color:var(--text-faint);font-family:'Nunito'">/15</span></div>
          <div class="load-sub">{{ 15 - $totalHeavyAssigned }} slots still open</div>
          <div class="prog-wrap">
            <div class="prog-lbl"><span>{{ round($totalHeavyAssigned / 15 * 100) }}% filled</span><span style="color:var(--berry)">Needs {{ 15 - $totalHeavyAssigned }} more</span></div>
            <div class="prog-bg"><div class="prog-fill @if($totalHeavyAssigned >= 12) red @endif" style="width:{{ $totalHeavyAssigned / 15 * 100 }}%"></div></div>
          </div>
        </div>
        <div class="load-box">
          <div class="sec-lbl">Admin / light laborers</div>
          <div class="load-num @if($totalLightAssigned >= 9) green @else green @endif">{{ $totalLightAssigned }} <span style="font-size:20px;color:var(--text-faint);font-family:'Nunito'">/10</span></div>
          <div class="load-sub">{{ 10 - $totalLightAssigned }} slots remaining</div>
          <div class="prog-wrap">
            <div class="prog-lbl"><span>{{ round($totalLightAssigned / 10 * 100) }}% filled</span><span style="color:var(--fern)">Near capacity</span></div>
            <div class="prog-bg"><div class="prog-fill" style="width:{{ $totalLightAssigned / 10 * 100 }}%"></div></div>
          </div>
        </div>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-users"></i><div><h3>Upcoming Shifts</h3></div></div>
        <div class="tbl-head" style="grid-template-columns:80px 1fr 80px 80px 80px">
          <span>Date</span><span>Shift</span><span>Heavy</span><span>Light</span><span>Status</span>
        </div>
        @forelse($shiftsWithLoad as $shift)
        <div class="tbl-row" style="grid-template-columns:80px 1fr 80px 80px 80px">
          <span class="sub">{{ $shift['start_date']->format('d M') }}</span>
          <span class="name">Shift #{{ $shift['id'] }}</span>
          <span class="sub">{{ $shift['heavy'] }}/{{ $shift['heavy_cap'] }}</span>
          <span class="sub">{{ $shift['light'] }}/{{ $shift['light_cap'] }}</span>
          <span><span class="pill p-met">Active</span></span>
        </div>
        @empty
        <div class="tbl-row" style="grid-template-columns:80px 1fr 80px 80px 80px">
          <span class="sub" colspan="5">No upcoming shifts</span>
        </div>
        @endforelse
      </div>
    </div>

    <!-- F25 — SERVICE HOURS -->
    <div class="page" id="page-hours">
      <div class="page-title">
        <div>
          <h2>Mandatory Service Hour Tracker</h2>
          <p>A ledger tracking whether each plot owner has met their monthly communal work requirement. Members below requirement risk plot suspension.</p>
          <span class="fn-badge">Function #25</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Total members</div><div class="s-val">{{ $teamMembers }}</div></div>
        <div class="stat"><div class="s-lbl">Requirement met</div><div class="s-val" style="color:var(--fern)">{{ $metRequirement }}</div><div class="s-sub">≥ {{ $requiredHours }} hrs logged</div></div>
        <div class="stat warn"><div class="s-lbl">In progress</div><div class="s-val">{{ $inProgress }}</div><div class="s-sub">partial hours</div></div>
        <div class="stat danger"><div class="s-lbl">At risk</div><div class="s-val">{{ $atRisk }}</div><div class="s-sub">0–2 hrs, may lose plot</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-clock"></i><div><h3>My Service Hours</h3><div class="head-sub">Your contribution this month</div></div></div>
          <div style="text-align:center;padding:20px;">
            <div style="font-family:'Playfair Display',serif;font-size:56px;font-weight:700;color:var(--text-dark)">{{ $totalHours }}</div>
            <div class="sub" style="margin-top:8px">total hours logged</div>
            <div class="prog-wrap" style="margin-top:20px">
              <div class="prog-lbl"><span>Monthly progress</span><span>{{ $thisMonthHours }} hrs this month</span></div>
              <div class="prog-bg"><div class="prog-fill @if($totalHours < $requiredHours) amber @endif" style="width:{{ min(($totalHours / $requiredHours) * 100, 100) }}%"></div></div>
            </div>
          </div>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-user"></i><div><h3>My Activity</h3><div class="head-sub">This month</div></div></div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;text-align:center;padding:10px 0;">
            <div><div style="font-size:32px;font-weight:700;color:var(--fern)">{{ $completedAssignments }}</div><div class="sub">shifts completed</div></div>
            <div><div style="font-size:32px;font-weight:700;color:var(--text-dark)">{{ $thisMonthHours }}</div><div class="sub">hours this month</div></div>
          </div>
        </div>
      </div>
    </div>

    <!-- F26 — SHIFTS & SWAPS -->
    <div class="page" id="page-shifts">
      <div class="page-title">
        <div>
          <h2>Shift Substitution Workflow</h2>
          <p>Allows volunteers to request a swap with another member when they cannot attend a scheduled communal workday.</p>
          <span class="fn-badge">Function #26</span>
        </div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-calendar"></i><div><h3>My Upcoming Shifts</h3></div></div>
          <div class="tbl-head" style="grid-template-columns:70px 1fr 80px 80px">
            <span>Date</span><span>Task</span><span>Type</span><span>Action</span>
          </div>
          @forelse($myAssignments as $assignment)
          <div class="tbl-row" style="grid-template-columns:70px 1fr 80px 80px">
            <span class="sub">{{ $assignment['date'] ?? 'TBD' }}</span>
            <span class="name">{{ $assignment['task'] }}</span>
            <span>
              @if($assignment['role'] == 'heavy')
              <span class="pill p-heavy">Heavy</span>
              @else
              <span class="pill p-light">Light</span>
              @endif
            </span>
            <button class="btn-swap" onclick="show('shifts',this)">Swap ↗</button>
          </div>
          @empty
          <div class="tbl-row" style="grid-template-columns:70px 1fr 80px 80px">
            <span class="sub" colspan="4">No upcoming shifts assigned</span>
          </div>
          @endforelse
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-arrows-exchange"></i><div><h3>Open Swap Requests</h3><div class="head-sub">{{ $openSwapRequests->count() }} members need a swap partner</div></div></div>
          @forelse($openSwapRequests as $swap)
          <div style="background:#FFFBEB;border:1.5px solid #FDE68A;border-radius:var(--r-md);padding:16px;margin-bottom:12px;">
            <div style="font-size:14px;font-weight:700;color:var(--soil)">{{ $swap['requester_name'] }}</div>
            <div style="font-size:12px;color:var(--text-muted);margin:5px 0">{{ $swap['assignment_task'] }}</div>
            <div style="font-size:11px;color:var(--text-faint)">Posted {{ $swap['created_at']->diffForHumans() }} · Awaiting match</div>
            <form method="POST" action="{{ route('volunteer.swap.respond', $swap['id']) }}" style="margin-top:12px">
              @csrf
              <input type="hidden" name="decision" value="accepted">
              <button type="submit" class="btn btn-primary" style="padding:7px 16px;font-size:12px"><i class="ti ti-check"></i>Accept swap</button>
            </form>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No open swap requests</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- F27 — EMERGENCY BROADCASTER -->
    <div class="page" id="page-broadcast">
      <div class="page-title">
        <div>
          <h2>Emergency Site-Status Broadcaster</h2>
          <p>Send immediate alerts to all members about site closures, hazards, or urgent announcements.</p>
          <span class="fn-badge">Function #27</span>
        </div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-broadcast"></i><div><h3>Compose Broadcast</h3></div></div>
          <form method="POST" action="{{ route('volunteer.alert.broadcast') }}">
            @csrf
            <div class="form-group">
              <div class="form-label">Alert type</div>
              <select name="severity" class="form-control">
                <option value="critical">🔴 Emergency closure</option>
                <option value="warning">🟠 Maintenance warning</option>
                <option value="info">🟡 Weather advisory</option>
                <option value="info">🔵 General announcement</option>
              </select>
            </div>
            <div class="form-group">
              <div class="form-label">Title</div>
              <input type="text" name="title" class="form-control" placeholder="Alert title" required>
            </div>
            <div class="form-group">
              <div class="form-label">Message</div>
              <textarea name="message" class="form-control" placeholder="e.g. Water main burst — all plots closed until further notice." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i> Broadcast to all members</button>
          </form>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-history"></i><div><h3>Recent Broadcasts</h3></div></div>
          @forelse($activeAlerts as $alert)
          <div class="alert @if($alert['severity'] == 'critical') danger @elseif($alert['severity'] == 'warning') warning @else info @endif">
            <i class="ti @if($alert['severity'] == 'critical') ti-alert-triangle @elseif($alert['severity'] == 'warning') ti-tool @else ti-info-circle @endif"></i>
            <div>
              <div class="alert-title">{{ $alert['title'] }}</div>
              <div class="alert-body">Sent {{ $alert['created_at'] }} · {{ $alert['severity'] }}</div>
            </div>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No active alerts</div>
          @endforelse
        </div>
      </div>
    </div>

    <!-- F28 — FUND VOTING -->
    <div class="page" id="page-voting">
      <div class="page-title">
        <div>
          <h2>Communal Fund Allocation Voting</h2>
          <p>Social consensus module for members to vote on how garden fees and communal funds are spent each season.</p>
          <span class="fn-badge">Function #28</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat sky"><div class="s-lbl">Active proposals</div><div class="s-val">{{ $openProposals->count() }}</div><div class="s-sub">open for voting</div></div>
        <div class="stat"><div class="s-lbl">Votes cast</div><div class="s-val" style="color:var(--fern)">{{ $totalVotesCast }}</div><div class="s-sub">total votes</div></div>
        <div class="stat warn"><div class="s-lbl">Total proposals</div><div class="s-val" style="font-size:20px">{{ $proposals->count() }}</div><div class="s-sub">all time</div></div>
        <div class="stat"><div class="s-lbl">Leading option</div><div class="s-val" style="font-size:18px">@if($leadingProposal)🌿 {{ Str::limit($leadingProposal['title'], 15) }} @else None @endif</div><div class="s-sub">@if($leadingProposal){{ $leadingProposal['percentage'] }}% of votes @endif</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-podium"></i><div><h3>Active Proposals</h3></div></div>
          @forelse($openProposals as $proposal)
          <div class="vote-item">
            <div class="vote-row"><span class="vote-name">{{ $proposal['title'] }}</span><span class="vote-pct" style="color:var(--fern)">{{ $proposal['percentage'] }}%</span></div>
            <div class="vote-bg"><div class="vote-fill" style="width:{{ $proposal['percentage'] }}%;background:var(--leaf)"></div></div>
            <div class="vote-sub">{{ $proposal['total'] }} votes · £{{ $proposal['estimated_cost'] }} · Ends {{ $proposal['voting_ends_at']->format('d M') }}</div>
            <form method="POST" action="{{ route('volunteer.proposals.vote', $proposal['id']) }}" style="margin-top:10px;display:flex;gap:8px">
              @csrf
              <button type="submit" name="vote" value="yes" class="btn btn-primary" style="padding:6px 12px;font-size:11px">✅ Yes</button>
              <button type="submit" name="vote" value="no" class="btn btn-ghost" style="padding:6px 12px;font-size:11px">❌ No</button>
            </form>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No open proposals</div>
          @endforelse
          <div style="margin-top:1.25rem">
            <button class="btn btn-ghost" onclick="document.getElementById('proposal-form').style.display='block'"><i class="ti ti-plus"></i> Propose new option</button>
          </div>
          <form id="proposal-form" method="POST" action="{{ route('volunteer.proposals.create') }}" style="display:none;margin-top:1rem;padding:1rem;background:var(--parch);border-radius:var(--r-md)">
            @csrf
            <div class="form-group">
              <input type="text" name="title" class="form-control" placeholder="Proposal title" required>
            </div>
            <div class="form-group">
              <textarea name="description" class="form-control" placeholder="Description" required></textarea>
            </div>
            <div class="form-group">
              <input type="number" name="estimated_cost" class="form-control" placeholder="Estimated cost (£)" required>
            </div>
            <div class="form-group">
              <input type="date" name="voting_ends_at" class="form-control" required min="{{ now()->addDay()->format('Y-m-d') }}">
            </div>
            <button type="submit" class="btn btn-primary">Submit Proposal</button>
          </form>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-history"></i><div><h3>Past Votes</h3></div></div>
          @foreach($proposals->where('status', '!=', 'open') as $proposal)
          <div style="padding:14px 0;border-bottom:1px solid var(--border)">
            <div style="font-weight:700;font-size:13px">{{ $proposal['title'] }}</div>
            <div class="sub" style="margin-top:4px;font-size:12px">{{ $proposal['percentage'] }}% · {{ $proposal['status'] }} · £{{ $proposal['estimated_cost'] }}</div>
          </div>
          @endforeach
          @if($proposals->where('status', '!=', 'open')->isEmpty())
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No past proposals</div>
          @endif
        </div>
      </div>
    </div>

    <!-- F29 — ACCESS LOG -->
    <div class="page" id="page-access">
      <div class="page-title">
        <div>
          <h2>Garden Security Access Log</h2>
          <p>Manages digital gate codes and records all member entries and exits for full site security traceability.</p>
          <span class="fn-badge">Function #29</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">On site now</div><div class="s-val" style="color:var(--fern)">{{ $onSiteNow }}</div></div>
        <div class="stat"><div class="s-lbl">Entries today</div><div class="s-val">{{ $entriesToday }}</div></div>
        <div class="stat"><div class="s-lbl">Exits today</div><div class="s-val">{{ $exitsToday }}</div></div>
        <div class="stat warn"><div class="s-lbl">Gate code</div><div class="s-val" style="font-size:16px">****</div><div class="s-sub">rotates weekly</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-door"></i><div><h3>Recent Access Log</h3><div class="head-sub">Today</div></div></div>
          <div class="acc-row hdr"><span>Time</span><span>Member</span><span>Gate</span><span>Type</span></div>
          @forelse($recentAccess as $log)
          <div class="acc-row">
            <span class="sub">{{ $log['time'] }}</span>
            <span class="name">{{ $log['member_name'] }}</span>
            <span class="sub">{{ $log['gate'] }}</span>
            <span>
              @if($log['action'] == 'entry')
              <span class="pill p-in">Entry</span>
              @else
              <span class="pill p-out">Exit</span>
              @endif
            </span>
          </div>
          @empty
          <div class="acc-row"><span class="sub" colspan="4">No recent access</span></div>
          @endforelse
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-key"></i><div><h3>Active Gate Codes</h3></div></div>
          <div style="display:flex;flex-direction:column;gap:12px">
            <div class="gate-box">
              <div class="gate-name">Main Gate</div>
              <div class="gate-code">●●●● ●●●●</div>
              <div class="gate-rotate">Rotates weekly</div>
            </div>
            <div class="gate-box">
              <div class="gate-name">Side Gate</div>
              <div class="gate-code">●●●● ●●●●</div>
              <div class="gate-rotate">Rotates weekly</div>
            </div>
          </div>
          <button class="btn btn-primary" style="margin-top:1.25rem"><i class="ti ti-refresh"></i>Rotate codes now</button>
        </div>
      </div>
    </div>

    <!-- F30 — MENTORSHIP -->
    <div class="page" id="page-mentorship">
      <div class="page-title">
        <div>
          <h2>Mentorship Pairing Algorithm</h2>
          <p>Automatically links experienced Master Gardeners with new plot owners based on shared growing interests and plot proximity.</p>
          <span class="fn-badge">Function #30</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat"><div class="s-lbl">Master Gardeners</div><div class="s-val" style="color:var(--fern)">11</div></div>
        <div class="stat"><div class="s-lbl">Active pairings</div><div class="s-val">{{ $activePairs }}</div></div>
        <div class="stat warn"><div class="s-lbl">Awaiting match</div><div class="s-val">{{ $awaitingPairs }}</div></div>
        <div class="stat"><div class="s-lbl">My mentor</div><div class="s-val" style="font-size:18px">@if($myMentorData) {{ $myMentorData['name'] }} @else None @endif</div><div class="s-sub">@if($myMentorData){{ $myMentorData['match_percentage'] }}% match @endif</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-user-plus"></i><div><h3>Request a Mentor</h3></div></div>
          @if($myMentorData)
          <div style="padding:20px;text-align:center;color:var(--fern)">
            <i class="ti ti-check-circle" style="font-size:40px"></i>
            <div style="margin-top:10px;font-weight:600">You have a mentor!</div>
            <div class="sub">{{ $myMentorData['name'] }}</div>
          </div>
          @else
          <form method="POST" action="{{ route('volunteer.mentor.pair') }}">
            @csrf
            <p style="margin-bottom:16px;color:var(--text-muted)">We'll match you with a Master Gardener based on your gardening interests.</p>
            <button type="submit" class="btn btn-primary"><i class="ti ti-plus"></i> Find my mentor</button>
          </form>
          @endif
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-users"></i><div><h3>My Mentorship</h3></div></div>
          @if($myMentorData)
          <div class="pair-card">
            <div class="av av-g">MG</div>
            <div class="pair-info">
              <div class="pname">{{ $myMentorData['name'] }} <span class="pill p-master" style="font-size:10px;padding:3px 9px">Master Gardener</span></div>
              <div class="pair-tags">
                @foreach($myMentorData['interests'] as $interest)
                <span class="tag">{{ $interest }}</span>
                @endforeach
              </div>
            </div>
            <div class="match-badge">{{ $myMentorData['match_percentage'] }}% match</div>
          </div>
          @else
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No active mentorship</div>
          @endif
        </div>
      </div>
    </div>

    <!-- F31 — INCIDENTS -->
    <div class="page" id="page-incidents">
      <div class="page-title">
        <div>
          <h2>Incident &amp; Hazard Reporting</h2>
          <p>Structured workflow for reporting safety issues with severity prioritization and full resolution tracking.</p>
          <span class="fn-badge">Function #31</span>
        </div>
      </div>
      <div class="stat-row">
        <div class="stat danger"><div class="s-lbl">Open incidents</div><div class="s-val">{{ $openIncidents }}</div></div>
        <div class="stat warn"><div class="s-lbl">In progress</div><div class="s-val">{{ $inProgressIncidents }}</div></div>
        <div class="stat"><div class="s-lbl">Resolved this month</div><div class="s-val" style="color:var(--fern)">{{ $resolvedIncidents }}</div></div>
        <div class="stat danger"><div class="s-lbl">Critical</div><div class="s-val">{{ $criticalIncidents }}</div><div class="s-sub">urgent action needed</div></div>
      </div>
      <div class="g2">
        <div class="card">
          <div class="card-head"><i class="ti ti-alert-circle"></i><div><h3>Recent Incidents</h3></div></div>
          @forelse($incidents as $incident)
          <div class="incident">
            <div class="sev-icon @if($incident['severity'] == 'critical' || $incident['severity'] == 'high') sev-h @elseif($incident['severity'] == 'medium') sev-m @else sev-l @endif">
              <i class="ti @if($incident['severity'] == 'critical' || $incident['severity'] == 'high') ti-alert-triangle @elseif($incident['severity'] == 'medium') ti-tool @else ti-check @endif"></i>
            </div>
            <div style="flex:1">
              <div class="inc-title">{{ $incident['title'] }}</div>
              <div class="inc-meta">{{ $incident['location'] }} · {{ $incident['reporter'] }}</div>
              <div class="inc-actions">
                <span class="pill @if($incident['status'] == 'open') p-open @elseif($incident['status'] == 'in_progress') p-inprog @else p-done @endif">
                  {{ ucfirst($incident['status']) }} · {{ ucfirst($incident['severity']) }}
                </span>
              </div>
            </div>
          </div>
          @empty
          <div style="padding:20px;text-align:center;color:var(--text-muted)">No incidents reported</div>
          @endforelse
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-plus"></i><div><h3>Report New Incident</h3></div></div>
          <form method="POST" action="{{ route('volunteer.incident.report') }}">
            @csrf
            <div class="form-group">
              <div class="form-label">Title</div>
              <input type="text" name="title" class="form-control" placeholder="e.g. Broken glass in path" required>
            </div>
            <div class="form-group">
              <div class="form-label">Location</div>
              <input type="text" name="location" class="form-control" placeholder="e.g. Path B, near Plot C-11" required>
            </div>
            <div class="form-group">
              <div class="form-label">Description</div>
              <textarea name="description" class="form-control" placeholder="Describe the hazard or incident clearly..." required></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><i class="ti ti-send"></i> Submit report</button>
          </form>
        </div>
      </div>
    </div>

    <!-- F32 — WEATHER & SHIFTS -->
    <div class="page" id="page-weather">
      <div class="page-title">
        <div>
          <h2>Weather-Driven Shift Cancellation</h2>
          <p>Automatically reschedules or cancels communal workdays when the forecast predicts heavy rain (≥8mm/hr) or extreme heat (≥36°C).</p>
          <span class="fn-badge">Function #32</span>
        </div>
      </div>
      <div class="g2" style="margin-bottom:1.25rem">
        <div class="card">
          <div class="card-head"><i class="ti ti-sun"></i><div><h3>Today — {{ now()->format('d M Y') }}</h3></div></div>
          <div style="display:flex;align-items:center;gap:20px;margin-bottom:16px">
            <i class="ti ti-sun" style="font-size:56px;color:var(--sun)"></i>
            <div>
              <div style="font-family:'Playfair Display',serif;font-size:38px;font-weight:600;color:var(--text-dark)">24°C</div>
              <div class="sub">Sunny · Wind 12 km/h · Humidity 38%</div>
            </div>
          </div>
          <span class="pill p-met" style="font-size:12px;padding:6px 16px">✓ All shifts confirmed — ideal conditions</span>
        </div>
        <div class="card">
          <div class="card-head"><i class="ti ti-settings"></i><div><h3>Cancellation Thresholds</h3></div></div>
          <div class="thresh-row cancel"><span class="thresh-label">Heavy rain ≥ 8mm/hr</span><span class="thresh-action">Auto-cancel</span></div>
          <div class="thresh-row cancel"><span class="thresh-label">Extreme heat ≥ 36°C</span><span class="thresh-action">Auto-cancel</span></div>
          <div class="thresh-row warn"><span class="thresh-label">Moderate rain 4–8mm/hr</span><span class="thresh-action">Admin warning</span></div>
          <div class="thresh-row warn"><span class="thresh-label">High wind ≥ 60 km/h</span><span class="thresh-action">Admin warning</span></div>
        </div>
      </div>
      <div class="card">
        <div class="card-head"><i class="ti ti-calendar"></i><div><h3>7-Day Forecast &amp; Shift Status</h3></div></div>
        <div class="forecast" style="margin-bottom:1.5rem">
          @foreach($upcomingShifts as $shift)
          <div class="w-day @if($shift['weather'] == 'rain') rain @endif">
            <div class="wday-name">{{ $shift['date'] }}</div>
            <i class="ti @if($shift['weather'] == 'clear') ti-sun @elseif($shift['weather'] == 'rain') ti-cloud-rain @else ti-cloud @endif" style="@if($shift['weather'] == 'clear') color:var(--sun) @elseif($shift['weather'] == 'rain') color:var(--sky) @endif"></i>
            <div class="wday-temp">{{ $shift['temp'] }}°C</div>
            @if($shift['cancelled'])<span class="cancel-flag">CANCELLED</span>@endif
          </div>
          @endforeach
        </div>
        <div class="sec-lbl">Recent Weather Actions</div>
        <div class="alert info" style="margin-top:10px">
          <i class="ti ti-info-circle"></i>
          <div><div class="alert-title">All shifts running normally</div><div class="alert-body">No weather cancellations triggered this week</div></div>
        </div>
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
  localStorage.setItem('activeTab', id);
}

// On page load, restore the last active tab
document.addEventListener('DOMContentLoaded', function() {
  let savedTab = localStorage.getItem('activeTab') || 'tasks';
  const btn = document.querySelector('.nav-btn[data-tab="' + savedTab + '"]');
  if (btn) {
    show(savedTab, btn);
  } else {
    // Fallback to first tab
    show('tasks', document.querySelector('.nav-btn[data-tab="tasks"]'));
  }
});
</script>
</body>
</html>