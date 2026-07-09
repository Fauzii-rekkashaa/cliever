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
    <p class="video-hint" id="videoHint">⚠️ Tonton video sampai selesai untuk melanjutkan.</p>

    @if($isSelesai)
    <div class="alert-admin-success" style="margin-top:1rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @endif

    <form method="POST" action="{{ route('pelajar.materi.selesai', $materi->id) }}" id="selesaiForm" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" id="btnSelesai" class="btn-continue-learning"
            {{ !$isSelesai ? 'disabled' : '' }}
            style="{{ !$isSelesai ? 'opacity:.4; cursor:not-allowed; pointer-events:none;' : '' }}">
            {{ $nextMateri ? 'Lanjut ke Materi Berikutnya' : 'Selesaikan Course' }}
        </button>
    </form>

    {{-- ── FILE / PDF ── --}}
    @elseif($materi->tipe === 'file' && $materi->file_materi)
    @php $ext = strtolower(pathinfo($materi->file_materi, PATHINFO_EXTENSION)); @endphp

    @if($ext === 'pdf')
    {{-- PDF: embed langsung + deteksi scroll --}}
    <p class="video-hint" id="pdfHint" style="{{ $isSelesai ? 'display:none;' : '' }}">
        ⚠️ Scroll PDF sampai halaman terakhir untuk melanjutkan.
    </p>
    <div id="pdfContainer" style="width:100%; height:600px; border:1.5px solid var(--gray-200); border-radius:var(--radius-md); overflow:hidden; margin-bottom:1rem; position:relative;">
        <iframe
            id="pdfFrame"
            src="{{ asset('storage/' . $materi->file_materi) }}#toolbar=0&navpanes=0&scrollbar=1"
            width="100%"
            height="100%"
            style="border:none;"
        ></iframe>
        {{-- Overlay scroll tracker (karena iframe cross-origin susah dideteksi) --}}
        <div id="pdfScrollTracker"
             style="position:absolute; top:0; left:0; width:100%; height:100%; overflow-y:auto; pointer-events:{{ $isSelesai ? 'none' : 'auto' }}; background:transparent; z-index:2; display:{{ $isSelesai ? 'none' : 'block' }};">
            {{-- Spacer panjang untuk simulasi scroll --}}
            <div id="pdfScrollContent" style="height:3000px; width:100%; background:transparent;"></div>
            <div id="pdfScrollEnd" style="height:1px;"></div>
        </div>
    </div>

    {{-- Link buka di tab baru --}}
    <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank"
       style="font-size:.85rem; color:#3b82f6; display:inline-block; margin-bottom:1rem;">
        🔗 Buka PDF di tab baru
    </a>

    @else
    {{-- File lain (JPG, PNG, dll) --}}
    <div class="file-preview-box">
        <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#3b82f6" stroke-width="1.5">
            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
        </svg>
        <p style="font-weight:600; margin-top:.75rem;">Dokumen Materi</p>
        <a href="{{ asset('storage/' . $materi->file_materi) }}" target="_blank"
           class="btn-form-submit" style="margin-top:1rem; display:inline-block; text-decoration:none;">
            Buka File
        </a>
    </div>
    @endif

    @if($isSelesai)
    <div class="alert-admin-success" style="margin-top:1rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @endif

    <form method="POST" action="{{ route('pelajar.materi.selesai', $materi->id) }}" id="selesaiForm" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" id="btnSelesai" class="btn-continue-learning"
            {{ !$isSelesai ? 'disabled' : '' }}
            style="{{ !$isSelesai ? 'opacity:.4; cursor:not-allowed; pointer-events:none;' : '' }}">
            {{ $nextMateri ? 'Lanjut ke Materi Berikutnya' : 'Selesaikan Course' }}
        </button>
    </form>

    {{-- ── TEKS ── --}}
    @else
    <div class="teks-content" id="teksContent">
        {{ $materi->konten ?? 'Tidak ada konten.' }}
    </div>

    @if($isSelesai)
    <div class="alert-admin-success" style="margin-top:1.5rem;">✅ Materi ini sudah kamu selesaikan.</div>
    @endif

    <form method="POST" action="{{ route('pelajar.materi.selesai', $materi->id) }}" id="selesaiForm" style="margin-top:1.5rem;">
        @csrf
        <button type="submit" id="btnSelesai" class="btn-continue-learning"
            {{ !$isSelesai ? 'disabled' : '' }}
            style="{{ !$isSelesai ? 'opacity:.4; cursor:not-allowed; pointer-events:none;' : '' }}">
            {{ $nextMateri ? 'Lanjut ke Materi Berikutnya' : 'Selesaikan Course' }}
        </button>
    </form>
    @endif

</div>

@endsection

@push('scripts')
<script>
const isSelesai = {{ $isSelesai ? 'true' : 'false' }};

function unlockButton() {
    const btn = document.getElementById('btnSelesai');
    if (btn && !isSelesai) {
        btn.disabled = false;
        btn.style.opacity = '1';
        btn.style.cursor = 'pointer';
        btn.style.pointerEvents = 'auto';

        // Hint hilang
        const hint = document.getElementById('videoHint')
                  || document.getElementById('pdfHint');
        if (hint) {
            hint.innerHTML = '✅ Kamu sudah membaca/menonton sampai selesai. Klik tombol di bawah untuk lanjut.';
            hint.style.background = '#f0fdf4';
            hint.style.color = '#16a34a';
            hint.style.borderColor = '#bbf7d0';
        }
    }
}

// ── VIDEO: unlock saat video ended ──────────────────────────
@if($materi->tipe === 'video' && $materi->file_materi && !$isSelesai)
const video = document.getElementById('materiVideo');
if (video) {
    let maxWatched = 0;

    // Cegah skip
    video.addEventListener('timeupdate', function() {
        if (video.currentTime > maxWatched + 1) {
            video.currentTime = maxWatched;
        } else {
            maxWatched = video.currentTime;
        }
    });

    // Unlock tombol saat video tamat
    video.addEventListener('ended', function() {
        unlockButton();

        // Auto submit form
        const form = document.getElementById('selesaiForm');
        if (form) form.submit();
    });
}
@endif

// ── PDF: unlock saat sudah scroll ke bawah ──────────────────
@if($materi->tipe === 'file' && !$isSelesai)
@php $ext = strtolower(pathinfo($materi->file_materi ?? '', PATHINFO_EXTENSION)); @endphp
@if($ext === 'pdf')
const tracker = document.getElementById('pdfScrollTracker');
if (tracker) {
    let unlocked = false;
    tracker.addEventListener('scroll', function() {
        if (unlocked) return;

        const scrollBottom = tracker.scrollTop + tracker.clientHeight;
        const totalHeight  = tracker.scrollHeight;

        // Sudah scroll lebih dari 90% dianggap sudah baca sampai akhir
        if (scrollBottom >= totalHeight * 0.90) {
            unlocked = true;
            unlockButton();
            // Hilangkan overlay tracker biar bisa scroll PDF aslinya
            tracker.style.display = 'none';
        }
    });
}
@endif

// File non-PDF: langsung unlock setelah 3 detik (sudah terlihat)
@php $extCheck = strtolower(pathinfo($materi->file_materi ?? '', PATHINFO_EXTENSION)); @endphp
@if($extCheck !== 'pdf')
setTimeout(unlockButton, 3000);
@endif
@endif

// ── TEKS: unlock setelah scroll ke bawah halaman ────────────
@if($materi->tipe === 'teks' && !$isSelesai)
let teksUnlocked = false;
window.addEventListener('scroll', function() {
    if (teksUnlocked) return;
    const scrollBottom = window.scrollY + window.innerHeight;
    const totalHeight  = document.documentElement.scrollHeight;
    if (scrollBottom >= totalHeight * 0.90) {
        teksUnlocked = true;
        unlockButton();
    }
});
// Kalau konten pendek (tidak perlu scroll), unlock otomatis setelah 5 detik
setTimeout(function() {
    if (!teksUnlocked) unlockButton();
}, 5000);
@endif
</script>
@endpush
