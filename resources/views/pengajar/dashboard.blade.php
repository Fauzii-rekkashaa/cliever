@extends('layouts.pengajar')

@section('title', 'Dashboard Pengajar - Cliever')

@section('content')

<div class="page-header" style="display:flex; align-items:flex-start; justify-content:space-between; flex-wrap:wrap; gap:1rem;">
    <div>
        <h1 class="page-title">Dashboard Overview</h1>
        <p class="page-subtitle">Kelola course dan materi pembelajaran Anda</p>
    </div>
    <a href="{{ route('pengajar.course.create') }}" class="btn-primary-admin">
        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
        Tambah Course
    </a>
</div>

<div class="stats-grid" style="grid-template-columns: repeat(3,1fr); margin-bottom:2rem;">
    <div class="stat-card">
        <div class="stat-icon stat-icon--green">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.75"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"/><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"/></svg>
        </div>
        <p class="stat-label">Total Course</p>
        <p class="stat-number">{{ $totalCourse }}</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon" style="background:#fff7ed;">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f97316" stroke-width="1.75"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/><polyline points="17 6 23 6 23 12"/></svg>
        </div>
        <p class="stat-label">Total Materi</p>
        <p class="stat-number">{{ $totalMateri }}</p>
    </div>
    <div class="stat-card">
        <div class="stat-icon stat-icon--blue">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.75"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 00-3-3.87"/><path d="M16 3.13a4 4 0 010 7.75"/></svg>
        </div>
        <p class="stat-label">Total Pelajar</p>
        <p class="stat-number">{{ $totalPelajar }}</p>
    </div>
</div>

<div style="display:flex; align-items:center; gap:1rem; margin-bottom:1.5rem;">
    <form method="GET" action="{{ route('pengajar.dashboard') }}" style="flex:1;">
        <div class="search-box">
            <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/></svg>
            <input type="text" name="search" class="search-input" placeholder="Cari course"
                value="{{ request('search') }}" autocomplete="off">
        </div>
    </form>
</div>

<div class="course-pengajar-grid">
    @forelse($courses as $course)
    @php
        $avgRating    = $course->reviews->avg('rating') ?? 0;
        $totalReviews = $course->reviews->count();
    @endphp
    <div class="course-pengajar-card">
        <div class="course-thumbnail">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
            @else
                <div class="course-thumbnail-placeholder"></div>
            @endif

            <div class="course-menu">
                <button class="course-menu-btn" onclick="toggleMenu({{ $course->id }})">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor">
                        <circle cx="12" cy="5" r="1.5"/><circle cx="12" cy="12" r="1.5"/><circle cx="12" cy="19" r="1.5"/>
                    </svg>
                </button>
                <div class="course-menu-dropdown" id="menu-{{ $course->id }}">
                    <a href="{{ route('pengajar.course.edit', $course->id) }}">Edit Course</a>
                    <form method="POST" action="{{ route('pengajar.course.destroy', $course->id) }}"
                          onsubmit="return confirm('Hapus course ini?')">
                        @csrf @method('DELETE')
                        <button type="submit">Hapus Course</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="course-pengajar-info">
            <h3 class="course-pengajar-judul">{{ $course->judul }}</h3>
            <p class="course-pengajar-by">oleh {{ Auth::user()->username }}</p>

            {{-- Rating mini --}}
            @if($totalReviews > 0)
            <div style="display:flex; align-items:center; gap:.35rem; margin-top:.4rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                <span style="font-family:var(--font-main); font-weight:700; font-size:.85rem; color:var(--gray-800);">
                    {{ number_format($avgRating, 1) }}
                </span>
                <span style="font-size:.78rem; color:var(--gray-400);">({{ $totalReviews }} ulasan)</span>
            </div>
            @else
            <p style="font-size:.78rem; color:var(--gray-400); margin-top:.4rem;">Belum ada ulasan</p>
            @endif

            <p class="course-pengajar-desc">{{ Str::limit($course->deskripsi, 80) }}</p>
        </div>

        <div class="course-pengajar-footer">
            <a href="{{ route('pengajar.materi', $course->id) }}" class="btn-kelola-materi">
                Kelola Materi ({{ $course->materi_count }})
            </a>
            <a href="{{ route('pengajar.course.reviews', $course->id) }}"
               class="btn-kelola-materi"
               style="background:var(--gray-50); color:var(--gray-700); border:1.5px solid var(--gray-200); margin-top:.5rem; display:flex; align-items:center; justify-content:center; gap:.4rem;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                </svg>
                Lihat Ulasan ({{ $totalReviews }})
            </a>
        </div>
    </div>
    @empty
    <div style="grid-column:1/-1; text-align:center; padding:3rem; color:#94a3b8; font-style:italic;">
        Belum ada course. Klik "Tambah Course" untuk mulai.
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
function toggleMenu(id) {
    const menu = document.getElementById('menu-' + id);
    menu.classList.toggle('show');
    document.querySelectorAll('.course-menu-dropdown').forEach(m => {
        if (m.id !== 'menu-' + id) m.classList.remove('show');
    });
}
document.addEventListener('click', function(e) {
    if (!e.target.closest('.course-menu')) {
        document.querySelectorAll('.course-menu-dropdown').forEach(m => m.classList.remove('show'));
    }
});
</script>
@endpush
