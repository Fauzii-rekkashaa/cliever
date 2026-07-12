@extends('layouts.pengajar')

@section('title', ($course ? 'Edit' : 'Tambah') . ' Course - Cliever')

@section('content')

<a href="{{ route('pengajar.dashboard') }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Kembali ke Dashboard
</a>

<div class="page-header">
    <h1 class="page-title">{{ $course ? 'Edit Course' : 'Buat Course' }}</h1>
    <p class="page-subtitle">{{ $course ? 'Perbarui informasi course' : 'Buat course baru untuk diajarkan' }}</p>
</div>

<div class="course-form-card">

    @if ($errors->any())
    <div class="alert-admin-error">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <form method="POST"
          action="{{ $course ? route('pengajar.course.update', $course->id) : route('pengajar.course.store') }}"
          enctype="multipart/form-data">
        @csrf
        @if($course) @method('PUT') @endif

        <div class="form-group form-group--full">
            <label class="form-label">Judul Course <span class="required">*</span></label>
            <input type="text" name="judul" class="form-input @error('judul') is-error @enderror"
                value="{{ old('judul', $course?->judul) }}" required placeholder="Contoh: Pengantar Pengembangan Web">
            @error('judul')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group form-group--full">
            <label class="form-label">Deskripsi</label>
            <textarea name="deskripsi" class="form-textarea @error('deskripsi') is-error @enderror"
                rows="4" placeholder="Jelaskan apa yang akan dipelajari di course ini...">{{ old('deskripsi', $course?->deskripsi) }}</textarea>
            @error('deskripsi')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-group form-group--full">
            <label class="form-label">Thumbnail</label>
            @if($course && $course->thumbnail)
            <div style="margin-bottom:.75rem;">
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="thumbnail" style="width:200px; border-radius:10px; border:1.5px solid var(--gray-200);">
            </div>
            @endif
            <input type="file" name="thumbnail" class="form-input @error('thumbnail') is-error @enderror"
                accept="image/jpg,image/jpeg,image/png">
            <span style="font-size:.78rem; color:var(--gray-400); margin-top:.25rem;">
                {{ $course ? 'Kosongkan jika tidak ingin mengubah thumbnail' : 'Format: JPG, PNG. Maks 2MB' }}
            </span>
            @error('thumbnail')<span class="form-error">{{ $message }}</span>@enderror
        </div>

        <div class="form-actions">
            <a href="{{ route('pengajar.dashboard') }}" class="btn-form-cancel">Batal</a>
            <button type="submit" class="btn-form-submit">
                {{ $course ? 'Simpan Perubahan' : 'Tambah Course' }}
            </button>
        </div>
    </form>
</div>

@endsection
