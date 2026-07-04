@extends('layouts.pelajar')

@section('title', 'My Course - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">My Course</h1>
    <p class="page-subtitle">Manage and track your enrolled courses</p>
</div>

@if(session('success'))
<div class="alert-admin-success">✅ {{ session('success') }}</div>
@endif

<div class="continue-grid">
    @forelse($enrollments as $enrollment)
    <div class="continue-card">
        <a href="{{ route('pelajar.course.detail', $enrollment->course->id) }}" style="text-decoration:none; color:inherit;">
            <div class="continue-thumbnail">
                @if($enrollment->course->thumbnail)
                    <img src="{{ asset('storage/' . $enrollment->course->thumbnail) }}" alt="{{ $enrollment->course->judul }}">
                @endif
            </div>
            <div class="continue-info">
                <p class="continue-judul">{{ $enrollment->course->judul }}</p>
                <p class="continue-by">by {{ $enrollment->course->pengajar->nama ?? $enrollment->course->pengajar->username }}</p>

                <div class="continue-progress-row">
                    <span>Progress</span>
                    <span>{{ $enrollment->progress ?? 0 }}%</span>
                </div>
                <div class="progress-bar-track">
                    <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
                </div>
            </div>
        </a>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; padding:3rem; color:var(--gray-400); font-style:italic;">
        Kamu belum mengikuti course apapun. Yuk jelajahi course di menu Browse!
    </div>
    @endforelse
</div>

@endsection
