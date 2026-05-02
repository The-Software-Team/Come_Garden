<nav class="navbar">
    <div class="nav-left">
        <a href="/dashboard" class="logo">Come-Garden</a>
    </div>

    <div class="nav-links">
        @foreach(config('navigation') as $item)
            <a href="{{ url($item['url']) }}" class="{{ request()->is($item['pattern']) ? 'active' : '' }}">
                {{ $item['label'] }}
            </a>
        @endforeach        
    </div>

    <div class="nav-right">
        <span>{{ auth()->user()->name ?? 'Guest' }}</span>
        <form method="POST" action="{{ route('logout') }}" style="display:inline;">
            @csrf
            <button type="submit">Logout</button>
        </form>
    </div>
</nav>