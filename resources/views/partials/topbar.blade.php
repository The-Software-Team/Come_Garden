<style>
/* ══════════════════════════════════════════════════════════
   TOPBAR  ·  layouts/partials/topbar.blade.php
   Prefix: topbar_
══════════════════════════════════════════════════════════ */

.topbar {
    height: 54px;
    background: var(--color-background-primary);
    border-bottom: 0.5px solid var(--color-border-tertiary);
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 24px;
    position: sticky;
    top: 0;
    z-index: 100;
    gap: 16px;
}

/* Logo */
.topbar_logo {
    display: flex;
    align-items: center;
    gap: 9px;
    text-decoration: none;
    flex-shrink: 0;
}
.topbar_logo_mark {
    width: 30px;
    height: 30px;
    border-radius: 8px;
    background: #3d7a2f;
    display: flex;
    align-items: center;
    justify-content: center;
}
.topbar_logo_mark i { font-size: 15px; color: #fff; }
.topbar_logo_name {
    font-family: var(--font-serif);
    font-size: 17px;
    font-weight: 500;
    color: var(--color-text-primary);
    letter-spacing: -0.01em;
}
.topbar_logo_name span { color: #3d7a2f; }

/* Nav links (module links for authenticated users) */
.topbar_nav {
    display: flex;
    align-items: center;
    gap: 2px;
    flex: 1;
    padding: 0 16px;
    overflow-x: auto;
    scrollbar-width: none;
}
.topbar_nav::-webkit-scrollbar { display: none; }

.topbar_nav_link {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 6px 12px;
    border-radius: var(--border-radius-md);
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-secondary);
    text-decoration: none;
    white-space: nowrap;
    transition: background 0.12s, color 0.12s;
}
.topbar_nav_link:hover { background: var(--color-background-secondary); color: var(--color-text-primary); }
.topbar_nav_link.active { background: #f1f7ec; color: #3d7a2f; }
.topbar_nav_link i { font-size: 14px; }

/* Right side */
.topbar_right {
    display: flex;
    align-items: center;
    gap: 10px;
    flex-shrink: 0;
}

/* Role badge */
.topbar_role {
    padding: 3px 9px;
    border-radius: 999px;
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.05em;
    text-transform: uppercase;
    border: 0.5px solid;
}
.topbar_role--member { background: #f1f7ec; color: #3d7a2f; border-color: rgba(61,122,47,.2); }
.topbar_role--admin  { background: #fdf4ff; color: #7e22ce; border-color: rgba(126,34,206,.2); }

/* User chip */
.topbar_user {
    display: flex;
    align-items: center;
    gap: 8px;
    padding: 5px 10px 5px 6px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: var(--color-background-secondary);
}
.topbar_avatar {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: #3d7a2f;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    font-weight: 700;
    color: #fff;
    flex-shrink: 0;
}
.topbar_user_name {
    font-size: 13px;
    font-weight: 500;
    color: var(--color-text-primary);
    max-width: 130px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

/* Logout form */
.topbar_logout_btn {
    height: 34px;
    padding: 0 14px;
    border: 0.5px solid var(--color-border-secondary);
    border-radius: var(--border-radius-md);
    background: transparent;
    color: var(--color-text-secondary);
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    transition: background 0.12s, color 0.12s, border-color 0.12s;
}
.topbar_logout_btn:hover {
    background: #fceaea;
    color: #7b1f1f;
    border-color: rgba(123,31,31,.2);
}
.topbar_logout_btn i { font-size: 13px; }

/* Admin dashboard link */
.topbar_admin_link {
    height: 34px;
    padding: 0 14px;
    border: 0.5px solid rgba(126,34,206,.2);
    border-radius: var(--border-radius-md);
    background: #fdf4ff;
    color: #7e22ce;
    font-size: 12px;
    font-weight: 500;
    cursor: pointer;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    text-decoration: none;
    transition: background 0.12s;
}
.topbar_admin_link:hover { background: #ede9fe; }

@media (max-width: 700px) {
    .topbar_nav { display: none; }
    .topbar_user_name { display: none; }
}
</style>

<nav class="topbar">

    {{-- Logo --}}
    <a href="{{ auth()->check() ? url('/dashboard') : url('/') }}" class="topbar_logo">
        <div class="topbar_logo_mark">
            <i class="ti ti-plant"></i>
        </div>
        <span class="topbar_logo_name">Come<span>-Garden</span></span>
    </a>

    {{-- Module nav — only for authenticated users --}}
    @auth
        <nav class="topbar_nav">
            <a href="{{ url('/dashboard') }}"
               class="topbar_nav_link {{ request()->is('dashboard*') ? 'active' : '' }}">
                <i class="ti ti-layout-dashboard"></i> Dashboard
            </a>
            <a href="{{ route('seedbank.profile') }}"
               class="topbar_nav_link {{ request()->is('seedbank*') ? 'active' : '' }}">
                <i class="ti ti-seeding"></i> Seed Bank
            </a>
            <a href="{{ url('/plots') }}"
               class="topbar_nav_link {{ request()->is('plots*') || request()->is('my-plots*') ? 'active' : '' }}">
                <i class="ti ti-map-2"></i> Plots
            </a>
            <a href="{{ route('marketplace.market') }}"
               class="topbar_nav_link {{ request()->is('marketplace*') ? 'active' : '' }}">
                <i class="ti ti-shopping-bag"></i> Market
            </a>
            <a href="{{ route('tools') }}"
               class="topbar_nav_link {{ request()->is('tools*') ? 'active' : '' }}">
                <i class="ti ti-tool"></i> Tools
            </a>
            <a href="{{ route('volunteer') }}"
               class="topbar_nav_link {{ request()->is('volunteer*') ? 'active' : '' }}">
                <i class="ti ti-heart-handshake"></i> Volunteer
            </a>
        </nav>
    @endauth

    {{-- Right: user info + logout --}}
    <div class="topbar_right">

        @auth

            {{-- Admin shortcut --}}
            @if(auth()->user()->is_admin ?? false)
                <a href="{{ route('admin.dashboard') }}" class="topbar_admin_link">
                    <i class="ti ti-shield"></i> Admin
                </a>
            @endif

            {{-- Role badge --}}
            <span class="topbar_role topbar_role--{{ (auth()->user()->is_admin ?? false) ? 'admin' : 'member' }}">
                {{ (auth()->user()->is_admin ?? false) ? 'Admin' : 'Member' }}
            </span>

            {{-- User chip --}}
            <div class="topbar_user">
                <div class="topbar_avatar">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <span class="topbar_user_name">{{ auth()->user()->name }}</span>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="topbar_logout_btn">
                    <i class="ti ti-logout"></i>
                    Log out
                </button>
            </form>

        @else

            {{-- Guest: Login + Register --}}
            <a href="{{ route('login') }}"
               style="height:34px;padding:0 14px;display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:var(--color-text-secondary);text-decoration:none;border:0.5px solid var(--color-border-secondary);border-radius:var(--border-radius-md);transition:background .12s;"
               onmouseover="this.style.background='var(--color-background-secondary)'"
               onmouseout="this.style.background='transparent'">
                Log in
            </a>
            <a href="{{ route('register') }}"
               style="height:34px;padding:0 16px;display:inline-flex;align-items:center;gap:6px;font-size:13px;font-weight:500;color:#fff;background:#3d7a2f;border-radius:var(--border-radius-md);text-decoration:none;transition:background .12s;"
               onmouseover="this.style.background='#5a9e48'"
               onmouseout="this.style.background='#3d7a2f'">
                <i class="ti ti-plant" style="font-size:13px;"></i>
                Join the Garden
            </a>

        @endguest

    </div>

</nav>