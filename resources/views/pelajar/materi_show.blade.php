@extends('layouts.pelajar')

@section('title', $materi->judul . ' - ' . $course->judul . ' - Cliever')

@section('content')

<a href="{{ route('pelajar.course.detail', $course->id) }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Kembali ke {{ $course->judul }}
</a>

<div class="materi-show-card">
    <p class="continue-by" style="margin-bottom:.4rem;">{{ $course->judul }}</p>
    <h1 class="course-detail-judul" style="font-size:1.5rem; margin-bottom:1.5rem;">{{ $materi->judul }}</h1>

    @if(session('success'))
    <div class="alert-admin-success">✅ {{ session('success') }}</div>
    @endif

    {{-- ── VIDEO ── --}}
    @if($materi->tipe === 'video' && $materi->file_materi)
    <div class="video-wrapper">
        <video id="materiVideo" controls controlsList="nodownload noplaybackrate" disablePictureInPicture>
            <source src="{{ asset('storage/' . $materi->file_materi) }}" type="video/mp4">
            Browser kamu tidak mendukung pemutaran video.
        </video>
    </div>
    <p class="video-hint">⚠️ Tonton video sampai selesai untuk melanjutkan ke materi berikutnya.</p>

    @if($isSelesai)
    <div class="alert-admin-success" style="margin-top:1rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @if($nextMateri)
    <a href="{{ route('pelajar.materi.show', $nextMateri->id) }}" class="btn-continue-learning" style="display:inline-block; text-decoration:none; width:auto; padding:.75rem 2rem;">
        Lanjut ke Materi Berikutnya →
    </a>
    @else
    <a href="{{ route('pelajar.course.detail', $course->id) }}" class="btn-continue-learning" style="display:inline-block; text-decoration:none; width:auto; padding:.75rem 2rem;">
        Kembali ke Course
    </a>
    @endif
    @endif

    {{-- ── FILE ── --}}
    @elseif($materi->tipe === 'file' && $materi->file_materi)
    <div class="file-preview-box">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg>
        <p style="font-weight:600; margin-top:.75rem;">Dokumen Materi</p>
        <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank" class="btn-form-submit" style="margin-top:1rem; display:inline-block; text-decoration:none;">
            Buka File
        </a>
    </div>

    @if(!$isSelesai)
    <form method="POST" action="{{ route('pelajar.materi.selesai', $materi->id) }}" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" class="btn-continue-learning">
            {{ $nextMateri ? 'Lanjut ke Materi Berikutnya' : 'Selesaikan Course' }}
        </button>
    </form>
    @else
    <div class="alert-admin-success" style="margin-top:1.5rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @endif

    {{-- ── TEKS ── --}}
    @else
    <div class="teks-content">
        {{ $materi->konten ?? 'Tidak ada konten.' }}
    </div>

    @if(!$isSelesai)
    <form method="POST" action="{{ route('pelajar.materi.selesai', $materi->id) }}" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" class="btn-continue-learning">
            {{ $nextMateri ? 'Lanjut ke Materi Berikutnya' : 'Selesaikan Course' }}
        </button>
    </form>
    @else
    <div class="alert-admin-success" style="margin-top:1.5rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @endif
    @endif

</div>

@endsection

@push('scripts')
<script>
@if($materi->tipe === 'video' && !$isSelesai)
const video = document.getElementById('materiVideo');
if (video) {
    // Cegah skip dengan seek bar (opsional, tetap kasih kontrol play/pause/volume)
    let maxWatched = 0;
    video.addEventListener('timeupdate', function() {
        if (video.currentTime > maxWatched + 1) {
            video.currentTime = maxWatched;
        } else {
            maxWatched = video.currentTime;
        }
    });

    video.addEventListener('ended', function() {
        // Submit form otomatis tandai selesai
        fetch('{{ route('pelajar.materi.selesai', $materi->id) }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
            },
            body: new FormData(document.createElement('form'))
        });
        // Redirect manual via form submit (lebih reliable daripada fetch)
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = '{{ route('pelajar.materi.selesai', $materi->id) }}';
        const csrf = document.createElement('input');
        csrf.type = 'hidden';
        csrf.name = '_token';
        csrf.value = document.querySelector('meta[name="csrf-token"]').content;
        form.appendChild(csrf);
        document.body.appendChild(form);
        form.submit();
    });
}
@endif
</script>
@endpush
