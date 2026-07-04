@extends('layouts.admin')

@section('title', $course->judul . ' - Detail Course - Cliever')

@section('content')

<a href="{{ route('admin.konfirmasi') }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Kembali ke Mengelola Course
</a>

<div class="course-detail-thumbnail">
    @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
    @endif
</div>

<div style="display:flex; align-items:center; gap:.75rem; margin-bottom:.75rem; flex-wrap:wrap;">
    <h1 class="course-detail-judul" style="margin-bottom:0;">{{ $course->judul }}</h1>
    @php
        $badgeClass = match($course->status) {
            'menunggu'  => 'badge-menunggu',
            'disetujui' => 'badge-disetujui',
            'ditolak'   => 'badge-ditolak',
        };
        $badgeLabel = match($course->status) {
            'menunggu'  => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak'   => 'Ditolak',
        };
    @endphp
    <span class="badge-course {{ $badgeClass }}">{{ $badgeLabel }}</span>
</div>

<p class="course-detail-desc">{{ $course->deskripsi }}</p>

<div class="course-detail-pengajar-info">
    <div class="pengajar-avatar" style="background:#3b82f6; width:40px; height:40px; font-size:.95rem;">
        {{ strtoupper(substr($course->pengajar->username ?? '?', 0, 1)) }}
    </div>
    <div>
        <p style="font-family:var(--font-main); font-weight:700; font-size:.9rem; color:var(--gray-800);">
            {{ $course->pengajar->nama ?? $course->pengajar->username ?? '-' }}
        </p>
        <p style="font-size:.8rem; color:var(--gray-400);">{{ $course->pengajar->email ?? '-' }}</p>
    </div>
</div>

@if(session('success'))
<div class="alert-admin-success" style="margin-top:1.5rem;">✅ {{ session('success') }}</div>
@endif

{{-- Materi Card --}}
<div class="materi-card" style="margin-top:1.5rem;">
    <div class="materi-card-header">
        <div>
            <h2 class="materi-card-title">Materi yang Diunggah</h2>
            <p class="materi-card-count">{{ $course->materi->count() }} materi</p>
        </div>
    </div>

    <div class="materi-list-wrap">
        @forelse($course->materi as $index => $item)
        <div class="materi-row">
            <div class="materi-number">{{ $index + 1 }}</div>
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
                @if($item->konten)
                <p style="font-size:.82rem; color:var(--gray-600); margin-top:.5rem; line-height:1.6;">{{ Str::limit($item->konten, 150) }}</p>
                @endif
            </div>

            <div class="materi-row-actions">
                @if($item->file_materi)
                <a href="{{ asset('storage/' . $item->file_materi) }}" target="_blank" class="materi-action-btn" title="Lihat File">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                @endif
                <form method="POST" action="{{ route('admin.konfirmasi.materi.destroy', $item->id) }}"
                      onsubmit="return confirm('Hapus materi \'{{ $item->judul }}\'? Aksi ini tidak bisa dibatalkan.')">
                    @csrf @method('DELETE')
                    <button type="submit" class="materi-action-btn materi-action-delete" title="Hapus Materi">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:3rem; color:#94a3b8; font-style:italic;">
            Pengajar belum menambahkan materi.
        </div>
        @endforelse
    </div>
</div>

{{-- Action buttons --}}
@if($course->status === 'menunggu')
<div style="display:flex; gap:1rem; margin-top:1.5rem;">
    <form method="POST" action="{{ route('admin.konfirmasi.setujui', $course->id) }}"
          onsubmit="return confirm('Setujui course ini?')">
        @csrf @method('PATCH')
        <button type="submit" class="btn-form-submit" style="background:linear-gradient(135deg,#22c55e,#16a34a); box-shadow:0 4px 14px rgba(34,197,94,.3);">
            ✓ Setujui Course
        </button>
    </form>
    <form method="POST" action="{{ route('admin.konfirmasi.tolak', $course->id) }}"
          onsubmit="return confirm('Tolak course ini?')">
        @csrf @method('PATCH')
        <button type="submit" class="btn-form-cancel" style="color:#ef4444; border-color:#fecaca;">
            ✕ Tolak Course
        </button>
    </form>
</div>
@endif

@endsection
