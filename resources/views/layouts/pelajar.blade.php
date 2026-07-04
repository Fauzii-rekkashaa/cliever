<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Pelajar - Cliever')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/pelajar.css') }}">
    @stack('styles')
</head>
<body>

<div class="admin-wrapper">

    <aside class="sidebar">
        <div class="sidebar-logo">
            <span class="logo-icon">✦</span>
            <span class="logo-text">Cliever</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('pelajar.dashboard') }}"
               class="nav-item {{ request()->routeIs('pelajar.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </span>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('pelajar.mycourse') }}"
               class="nav-item {{ request()->routeIs('pelajar.mycourse') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <rect x="2" y="3" width="20" height="14" rx="2" ry="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/>
                    </svg>
                </span>
                <span class="nav-label">Course Saya</span>
            </a>

            <a href="{{ route('pelajar.browse') }}"
               class="nav-item {{ request()->routeIs('pelajar.browse') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="12" r="10"/><polygon points="16.24 7.76 14.12 14.12 7.76 16.24 9.88 9.88 16.24 7.76"/>
                    </svg>
                </span>
                <span class="nav-label">Jelajahi</span>
            </a>

            <a href="{{ route('pelajar.sertifikat') }}"
               class="nav-item {{ request()->routeIs('pelajar.sertifikat') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <circle cx="12" cy="8" r="6"/><path d="M9 14l-2 8 5-3 5 3-2-8"/>
                    </svg>
                </span>
                <span class="nav-label">Sertifikat</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <span class="user-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="user-name">{{ Auth::user()->nama ?? Auth::user()->username }}</span>
            </div>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="logout-btn">
                    <span class="nav-icon">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                        </svg>
                    </span>
                    <span class="nav-label">Keluar</span>
                </button>
            </form>
        </div>
    </aside>

    <main class="admin-main">
        @yield('content')
    </main>

</div>

@stack('scripts')
</body>
</html>
