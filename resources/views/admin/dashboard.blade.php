@extends('layouts.admin')

@section('title', 'Dashboard Admin - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">Dashboard Overview</h1>
    <p class="page-subtitle">Selamat datang kembali! Berikut ringkasan aktivitas hari ini.</p>
</div>

<div class="stats-grid">

    {{-- Total Pelajar --}}
    <div class="stat-card">
        <div class="stat-icon stat-icon--blue">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/>
                <path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/>
            </svg>
        </div>
        <p class="stat-label">Total Pelajar</p>
        <p class="stat-number">{{ $totalPelajar }}</p>
    </div>

    {{-- Total Course --}}
    <div class="stat-card">
        <div class="stat-icon stat-icon--green">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/>
            </svg>
        </div>
        <p class="stat-label">Total Course</p>
        <p class="stat-number">{{ $totalCourse }}</p>
    </div>

    {{-- Course Dikonfirmasi --}}
    <div class="stat-card">
        <div class="stat-icon stat-icon--purple">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#a855f7" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/>
            </svg>
        </div>
        <p class="stat-label">Course Dikonfirmasi</p>
        <p class="stat-number">{{ $courseDikonfirmasi }}</p>
    </div>

    {{-- Menunggu Konfirmasi --}}
    <div class="stat-card">
        <div class="stat-icon stat-icon--red">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <p class="stat-label">Menunggu Konfirmasi</p>
        <p class="stat-number">{{ $menungguKonfirmasi }}</p>
    </div>

</div>

@endsection
