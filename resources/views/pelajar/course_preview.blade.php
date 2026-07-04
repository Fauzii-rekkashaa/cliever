@extends('layouts.pelajar')

@section('title', $course->judul . ' - Cliever')

@section('content')

<a href="{{ route('pelajar.mycourse') }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Back to My Course
</a>

<div class="course-detail-layout">

    <div class="course-detail-main">

        <div class="course-detail-thumbnail">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
            @endif
        </div>

        <h1 class="course-detail-judul">{{ $course->judul }}</h1>
        <p class="continue-by" style="margin-bottom:1.25rem;">by {{ $course->pengajar->nama ?? $course->pengajar->username }}</p>
        <p class="course-detail-desc">{{ $course->deskripsi }}</p>

        @if(session('success'))
        <div class="alert-admin-success">✅ {{ session('success') }}</div>
        @endif

    </div>

    <div class="course-detail-sidebar">
        <div class="progress-panel">
            <form method="POST" action="{{ route('pelajar.enroll', $course->id) }}">
                @csrf
                <button type="submit" class="btn-continue-learning">Enroll Now</button>
            </form>
        </div>
    </div>

</div>

@endsection
