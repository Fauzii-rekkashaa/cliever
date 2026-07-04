@extends('layouts.pelajar')

@section('title', 'Dashboard - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">Selamat datang kembali, {{ Auth::user()->nama ?? Auth::user()->username }}!</h1>
    <p class="page-subtitle">Lanjutkan perjalanan belajarmu</p>
</div>

{{-- Learning Progress --}}
<div class="progress-card">
    <h2 class="progress-card-title">Progres Belajar</h2>

    @forelse($enrollments as $enrollment)
    <div class="progress-item">
        <div class="progress-item-header">
            <div>
                <p class="progress-item-judul">{{ $enrollment->course->judul }}</p>
                <p class="progress-item-by">oleh {{ $enrollment->course->pengajar->nama ?? $enrollment->course->pengajar->username }}</p>
            </div>
            <span class="progress-percent">{{ $enrollment->progress ?? 0 }}%</span>
        </div>
        <div class="progress-bar-track">
            <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
        </div>
    </div>
    @empty
    <p style="color:var(--gray-400); font-style:italic; padding:1rem 0;">Belum ada course yang diikuti.</p>
    @endforelse
</div>

{{-- Continue Learning --}}
<div style="margin-top:2.5rem;">
    <h2 class="page-title" style="font-size:1.5rem; margin-bottom:1.5rem;">Lanjutkan Belajar</h2>

    <div class="continue-grid">
        @forelse($enrollments as $enrollment)
        <div class="continue-card">
            <div class="continue-thumbnail">
                @if($enrollment->course->thumbnail)
                    <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->judul }}">
                @endif
            </div>
            <div class="continue-info">
                <p class="continue-judul">{{ $enrollment->course->judul }}</p>
                <p class="continue-by">oleh {{ $enrollment->course->pengajar->nama ?? $enrollment->course->pengajar->username }}</p>

                <div class="continue-progress-row">
                    <span>Progres</span>
                    <span>{{ $enrollment->progress ?? 0 }}%</span>
                </div>
                <div class="progress-bar-track">
                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                </div>
            </div>
        </div>
        @empty
        <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--gray-400); font-style:italic;">
            Belum ada course. Yuk mulai belajar dengan klik menu Browse!
        </div>
        @endforelse
    </div>
</div>

@endsection
