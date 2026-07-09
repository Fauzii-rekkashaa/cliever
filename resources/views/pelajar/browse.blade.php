@extends('layouts.pelajar')

@section('title', 'Browse Course - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">Jelajahi Kursus</h1>
    <p class="page-subtitle">Temukan keterampilan baru dan perluas pengetahuan Anda</p>
</div>

@if(session('success'))
<div class="alert-admin-success">✅ {{ session('success') }}</div>
@endif

<form method="GET" action="{{ route('pelajar.browse') }}" style="margin-bottom:2rem;">
    <div class="search-box" style="max-width:500px;">
        <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
            <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
        </svg>
        <input type="text" name="search" class="search-input" placeholder="Search Course"
            value="{{ request('search') }}" autocomplete="off">
    </div>
</form>

<div class="browse-grid">
    @forelse($courses as $course)
    <a href="{{ route('pelajar.course.preview', $course->id) }}" class="browse-card" style="text-decoration:none; color:inherit; display:block;">
        <div class="continue-thumbnail">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
            @endif
        </div>
        <div class="browse-info">
            <p class="continue-judul">{{ $course->judul }}</p>
            <p class="continue-by">by {{ $course->pengajar->nama ?? $course->pengajar->username }}</p>
            <span class="enroll-btn">Enroll Now</span>
        </div>
    </a>
    @empty
    <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--gray-400); font-style:italic;">
        Tidak ada course yang tersedia saat ini.
    </div>
    @endforelse
</div>

@endsection
