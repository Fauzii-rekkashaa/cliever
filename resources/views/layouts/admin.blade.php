<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Cliever')</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="{{ asset('css/admin.css') }}">

    @stack('styles')
</head>
<body>

<div class="admin-wrapper">

    <aside class="sidebar" id="sidebar">

        <div class="sidebar-logo">
            <span class="logo-icon">✦</span>
            <span class="logo-text">Cliever</span>
        </div>

        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}"
               class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
                    </svg>
                </span>
                <span class="nav-label">Dashboard</span>
            </a>

            <a href="{{ route('admin.pelajar') }}"
               class="nav-item {{ request()->routeIs('admin.pelajar') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
                    </svg>
                </span>
                <span class="nav-label">Data Pelajar</span>
            </a>

            <a href="{{ route('admin.pengajar') }}"
               class="nav-item {{ request()->routeIs('admin.pengajar') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                </span>
                <span class="nav-label">Data Pengajar</span>
            </a>

            <a href="{{ route('admin.konfirmasi') }}"
               class="nav-item {{ request()->routeIs('admin.konfirmasi*') ? 'active' : '' }}">
                <span class="nav-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
                    </svg>
                </span>
                <span class="nav-label">Mengelola Course</span>
            </a>
        </nav>

        <div class="sidebar-footer">
            <div class="sidebar-user">
                <span class="user-icon">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"/><circle cx="12" cy="7" r="4"/>
                    </svg>
                </span>
                <span class="user-name">{{ Auth::user()->username }}</span>
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
