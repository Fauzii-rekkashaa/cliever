@extends('layouts.pengajar')

@section('title', $course->judul . ' - Materi - Cliever')

@section('content')

<a href="{{ route('pengajar.dashboard') }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Kembali ke Dashboard
</a>

<div class="course-detail-thumbnail">
    @if($course->thumbnail)
        <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
    @endif
</div>

<h1 class="course-detail-judul">{{ $course->judul }}</h1>
<p class="course-detail-desc">{{ $course->deskripsi }}</p>

@if(session('success'))
<div class="alert-admin-success" style="margin-top:1.5rem;">✅ {{ session('success') }}</div>
@endif

<div class="materi-card">
    <div class="materi-card-header">
        <div>
            <h2 class="materi-card-title">Materi Pembelajaran</h2>
            <p class="materi-card-count">{{ $materi->count() }} materi tersedia</p>
        </div>
        <button class="btn-primary-admin" onclick="openModal()">
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="12" y1="5" x2="12" y2="19"/><line x1="5" y1="12" x2="19" y2="12"/></svg>
            Tambah Materi
        </button>
    </div>

    <div class="materi-list-wrap">
        @forelse($materi as $index => $item)
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
                <p style="font-size:.82rem; color:var(--gray-600); margin-top:.4rem;">{{ Str::limit($item->konten, 100) }}</p>
                @endif
            </div>

            <div class="materi-row-actions">
                @if($item->file_materi)
                <a href="{{ asset('storage/' . $item->file_materi) }}" target="_blank" class="materi-action-btn" title="Lihat File yang Diupload">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"/><circle cx="12" cy="12" r="3"/></svg>
                </a>
                @endif
                <form method="POST" action="{{ route('pengajar.materi.destroy', $item->id) }}"
                      onsubmit="return confirm('Hapus materi ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="materi-action-btn materi-action-delete" title="Hapus">
                        <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/></svg>
                    </button>
                </form>
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:3rem; color:#94a3b8; font-style:italic;">
            Belum ada materi. Klik "Tambah Materi" untuk mulai.
        </div>
        @endforelse
    </div>
</div>

{{-- Modal Tambah Materi --}}
<div class="modal-overlay" id="modalOverlay" onclick="closeModalOutside(event)">
    <div class="modal-box">
        <div class="modal-header">
            <h3>Tambah Materi</h3>
            <button onclick="closeModal()" class="modal-close">&times;</button>
        </div>

        @if ($errors->any())
        <div class="alert-admin-error" style="margin:0 1.5rem 1rem;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        <form method="POST" action="{{ route('pengajar.materi.store', $course->id) }}" enctype="multipart/form-data">
            @csrf
            <div class="modal-body">
                <div class="form-group form-group--full">
                    <label class="form-label">Judul Materi <span class="required">*</span></label>
                    <input type="text" name="judul" class="form-input" required placeholder="Contoh: Memulai dengan HTML">
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">Upload File (Video/Dokumen)</label>
                    <input type="file" name="file_materi" class="form-input" accept=".mp4,.pdf,.jpg,.jpeg,.png">
                    <span style="font-size:.78rem; color:var(--gray-400); margin-top:.25rem; display:block;">
                        Video: format MP4 &nbsp;|&nbsp; Dokumen: PDF, JPG, PNG &nbsp;|&nbsp; Maks 10MB. Kosongkan kalau materi cuma berupa teks.
                    </span>
                </div>

                <div class="form-group form-group--full">
                    <label class="form-label">Isi / Deskripsi Materi</label>
                    <textarea name="konten" class="form-textarea" rows="5" placeholder="Tulis penjelasan materi di sini. Wajib diisi kalau tidak upload file."></textarea>
                </div>
            </div>

            <div class="modal-footer">
                <button type="button" onclick="closeModal()" class="btn-form-cancel">Batal</button>
                <button type="submit" class="btn-form-submit">Tambah Materi</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
function openModal() { document.getElementById('modalOverlay').classList.add('show'); }
function closeModal() { document.getElementById('modalOverlay').classList.remove('show'); }
function closeModalOutside(e) { if (e.target.id === 'modalOverlay') closeModal(); }

@if($errors->any())
    document.addEventListener('DOMContentLoaded', () => openModal());
@endif
</script>
@endpush
