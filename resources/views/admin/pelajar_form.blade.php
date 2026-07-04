@extends('layouts.admin')

@section('title', ($user ? 'Edit' : 'Tambah') . ' Pelajar - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">{{ $user ? 'Edit Pelajar' : 'Tambah Pelajar' }}</h1>
    <p class="page-subtitle">{{ $user ? 'Perbarui data pelajar' : 'Tambah pelajar baru' }}</p>
</div>

<div class="form-card">

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
          action="{{ $user ? route('admin.pelajar.update', $user->id) : route('admin.pelajar.store') }}">
        @csrf
        @if($user) @method('PUT') @endif

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Nama Lengkap</label>
                <input type="text" name="nama" class="form-input @error('nama') is-error @enderror"
                    value="{{ old('nama', $user?->nama) }}" placeholder="Masukkan nama lengkap">
                @error('nama')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">Username <span class="required">*</span></label>
                <input type="text" name="username" class="form-input @error('username') is-error @enderror"
                    value="{{ old('username', $user?->username) }}" required placeholder="Masukkan username">
                @error('username')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-row">
            <div class="form-group">
                <label class="form-label">Email <span class="required">*</span></label>
                <input type="email" name="email" class="form-input @error('email') is-error @enderror"
                    value="{{ old('email', $user?->email) }}" required placeholder="Masukkan email">
                @error('email')<span class="form-error">{{ $message }}</span>@enderror
            </div>
            <div class="form-group">
                <label class="form-label">
                    Password {{ $user ? '(kosongkan jika tidak diubah)' : '*' }}
                </label>
                <input type="password" name="password"
                    class="form-input @error('password') is-error @enderror"
                    {{ $user ? '' : 'required' }} placeholder="Masukkan password" autocomplete="new-password">
                @error('password')<span class="form-error">{{ $message }}</span>@enderror
            </div>
        </div>

        <div class="form-actions">
            <a href="{{ route('admin.pelajar') }}" class="btn-form-cancel">Batal</a>
            <button type="submit" class="btn-form-submit">
                {{ $user ? 'Simpan Perubahan' : 'Tambah Pelajar' }}
            </button>
        </div>
    </form>
</div>

@endsection
