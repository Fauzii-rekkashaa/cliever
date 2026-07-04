@extends('layouts.admin')

@section('title', 'Mengelola Course - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">Mengelola Course</h1>
    <p class="page-subtitle">Tinjau dan setujui course yang diajukan</p>
</div>

{{-- Stats Cards --}}
<div class="konfirmasi-stats">
    <div class="kstat-card kstat-yellow">
        <div class="kstat-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#f59e0b" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/>
            </svg>
        </div>
        <div class="kstat-info">
            <p class="kstat-label">Menunggu Persetujuan</p>
            <p class="kstat-number">{{ $menunggu }}</p>
        </div>
    </div>

    <div class="kstat-card kstat-green">
        <div class="kstat-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#22c55e" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/>
            </svg>
        </div>
        <div class="kstat-info">
            <p class="kstat-label">Disetujui</p>
            <p class="kstat-number">{{ $disetujui }}</p>
        </div>
    </div>

    <div class="kstat-card kstat-red">
        <div class="kstat-icon">
            <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#ef4444" stroke-width="1.75" stroke-linecap="round" stroke-linejoin="round">
                <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
            </svg>
        </div>
        <div class="kstat-info">
            <p class="kstat-label">Ditolak</p>
            <p class="kstat-number">{{ $ditolak }}</p>
        </div>
    </div>
</div>

{{-- Table Card --}}
<div class="table-card">

    <div class="table-search-wrap">
        <form method="GET" action="{{ route('admin.konfirmasi') }}" id="filterForm">
            <div class="search-box" style="margin-bottom:.875rem">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input
                    type="text"
                    name="search"
                    class="search-input"
                    placeholder="Cari judul course atau pengajar"
                    value="{{ request('search') }}"
                    autocomplete="off"
                >
            </div>

            <div class="filter-tabs">
                @foreach(['semua' => 'Semua', 'menunggu' => 'Menunggu', 'disetujui' => 'Disetujui', 'ditolak' => 'Ditolak'] as $val => $label)
                <button type="submit" name="status" value="{{ $val }}"
                    class="filter-tab {{ request('status', 'semua') === $val ? 'active' : '' }}">
                    {{ $label }}
                </button>
                @endforeach
            </div>
        </form>
    </div>

    {{-- Course List --}}
    <div class="course-list">
        @forelse ($courses as $course)
        <div class="course-item">
            <div class="course-item-content">
                <div class="course-item-header">
                    <h3 class="course-judul">{{ $course->judul }}</h3>
                    @php
                        $badgeClass = match($course->status) {
                            'menunggu'  => 'badge-menunggu',
                            'disetujui' => 'badge-disetujui',
                            'ditolak'   => 'badge-ditolak',
                            default     => 'badge-menunggu',
                        };
                        $badgeLabel = match($course->status) {
                            'menunggu'  => 'Menunggu',
                            'disetujui' => 'Disetujui',
                            'ditolak'   => 'Ditolak',
                            default     => 'Menunggu',
                        };
                    @endphp
                    <span class="badge-course {{ $badgeClass }}">{{ $badgeLabel }}</span>
                </div>
                <p class="course-desc">{{ $course->deskripsi }}</p>
                <p class="course-pengajar">Pengajar: {{ $course->pengajar->username ?? '-' }}</p>
            </div>

            <div class="course-item-actions">
                {{-- Lihat --}}
                <a href="{{ route('admin.konfirmasi.show', $course->id) }}" class="btn-course-action btn-view" title="Lihat Detail">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/>
                    </svg>
                </a>

                {{-- Tolak --}}
                <form method="POST" action="{{ route('admin.konfirmasi.tolak', $course->id) }}"
                      onsubmit="return confirm('Tolak course ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-course-action btn-tolak" title="Tolak">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><line x1="15" y1="9" x2="9" y2="15"/><line x1="9" y1="9" x2="15" y2="15"/>
                        </svg>
                    </button>
                </form>

                {{-- Setujui --}}
                <form method="POST" action="{{ route('admin.konfirmasi.setujui', $course->id) }}"
                      onsubmit="return confirm('Setujui course ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-course-action btn-setujui" title="Setujui">
                        <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <circle cx="12" cy="12" r="10"/><polyline points="9 12 11 14 15 10"/>
                        </svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div class="course-empty">Tidak ada course ditemukan.</div>
        @endforelse
    </div>

    @if ($courses->hasPages())
    <div class="table-pagination" style="padding:1.25rem 1.5rem">
        {{ $courses->withQueryString()->links() }}
    </div>
    @endif

</div>

@endsection
