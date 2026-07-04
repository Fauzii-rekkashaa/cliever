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

        <div class="materi-card">
            <div class="materi-card-header">
                <h2 class="materi-card-title">Course Content</h2>
            </div>

            <div class="materi-list-wrap">
                @forelse($materi as $index => $item)
                @php
                    $isSelesai = $progressMap[$item->id] ?? false;
                    $isLocked  = $lockedMap[$item->id] ?? false;
                @endphp

                @if($isLocked)
                <div class="materi-row materi-row-locked">
                    <div class="materi-check-btn materi-check-locked">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </div>
                    <div class="materi-row-info">
                        <p class="materi-row-judul" style="color:var(--gray-400);">{{ $item->judul }}</p>
                        <div class="materi-row-meta">
                            <span class="materi-meta-item">Selesaikan materi sebelumnya untuk membuka</span>
                        </div>
                    </div>
                </div>
                @else
                <a href="{{ route('pelajar.materi.show', $item->id) }}" class="materi-row materi-row-clickable" style="text-decoration:none; color:inherit;">
                    <div class="materi-check-btn {{ $isSelesai ? 'materi-check-done' : '' }}">
                        @if($isSelesai)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                        {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="materi-row-info">
                        <p class="materi-row-judul">{{ $item->judul }}</p>
                        <div class="materi-row-meta">
                            @if($item->tipe === 'video')
                                <span class="materi-meta-item">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                                    Video
                                </span>
                            @elseif($item->tipe === 'file')
                                <span class="materi-meta-item">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
                                    File
                                </span>
                            @else
                                <span class="materi-meta-item">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg>
                                    Teks
                                </span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @empty
                <div style="text-align:center; padding:3rem; color:#94a3b8; font-style:italic;">
                    Pengajar belum menambahkan materi untuk course ini.
                </div>
                @endforelse
            </div>
        </div>

    </div>

    <div class="course-detail-sidebar">
        <div class="progress-panel">
            <div class="progress-panel-header">
                <span>Your Progress</span>
                <span class="progress-percent">{{ $enrollment->progress ?? 0 }}%</span>
            </div>
            <div class="progress-bar-track" style="margin-bottom:.75rem;">
                <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
            </div>
            <p class="progress-panel-sub">{{ $selesaiCount }} of {{ $totalMateri }} lessons completed</p>

            @php
                $nextUnlocked = $materi->first(fn($m) => !($lockedMap[$m->id] ?? false) && !($progressMap[$m->id] ?? false));
            @endphp
            @if($nextUnlocked)
            <a href="{{ route('pelajar.materi.show', $nextUnlocked->id) }}" class="btn-continue-learning" style="display:block; text-align:center; text-decoration:none;">
                Continue Learning
            </a>
            @endif
        </div>
    </div>

</div>

@endsection
