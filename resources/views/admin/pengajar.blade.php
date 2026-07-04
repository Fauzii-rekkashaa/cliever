@extends('layouts.admin')

@section('title', 'Data Pengajar - Cliever')

@section('content')

<div class="page-header">
    <div>
        <h1 class="page-title">Data Pengajar</h1>
        <p class="page-subtitle">Kelola data pengajar</p>
    </div>
</div>

@if(session('success'))
<div class="alert-admin-success">✅ {{ session('success') }}</div>
@endif

<div class="table-card">

    <div class="table-search-wrap">
        <form method="GET" action="{{ route('admin.pengajar') }}">
            <div class="search-box">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="search-input"
                    placeholder="Cari Username, email, atau keahlian pengajar"
                    value="{{ request('search') }}" autocomplete="off">
            </div>
        </form>
    </div>

    <div class="pengajar-grid">
        @forelse ($pengajar as $user)
        <div class="pengajar-card">
            <div class="pengajar-card-header">
                <div class="pengajar-avatar" style="background: {{ $user->avatar_color }}">
                    {{ strtoupper(substr($user->username, 0, 1)) }}
                </div>
                <div class="pengajar-name">{{ $user->username }}</div>
                @if($user->status === 'aktif')
                    <span class="badge-status badge-aktif">Aktif</span>
                @elseif($user->status === 'menunggu')
                    <span class="badge-status badge-pending">Menunggu</span>
                @else
                    <span class="badge-status badge-nonaktif">Ditolak</span>
                @endif
            </div>

            {{-- ganti dari $user->bio ke $user->deskripsi_pengajar --}}
            <p class="pengajar-bio">{{ $user->deskripsi_pengajar ?? 'Belum ada deskripsi.' }}</p>

            <div class="pengajar-info">
                <div class="pengajar-info-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                    <span>{{ $user->email }}</span>
                </div>
                @if($user->keahlian)
                <div class="pengajar-info-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M22 10v6M2 10l10-5 10 5-10 5z"/><path d="M6 12v5c3 3 9 3 12 0v-5"/>
                    </svg>
                    <span>{{ $user->keahlian }}</span>
                </div>
                @endif

                @if($user->sertifikat)
                <div class="pengajar-info-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <a href="{{ asset('storage/' . $user->sertifikat) }}" target="_blank" class="sertifikat-link">
                        Lihat Dokumen Sertifikat
                    </a>
                </div>
                @else
                <div class="pengajar-info-item">
                    <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/>
                    </svg>
                    <span style="color:#94a3b8; font-style:italic;">Tidak ada dokumen</span>
                </div>
                @endif
            </div>

            <div class="pengajar-actions">
                @if($user->status === 'menunggu')
                <form method="POST" action="{{ route('admin.pengajar.konfirmasi', $user->id) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-pengajar-konfirmasi">✓ Konfirmasi</button>
                </form>
                <form method="POST" action="{{ route('admin.pengajar.tolak', $user->id) }}"
                      onsubmit="return confirm('Tolak pengajar ini?')">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn-pengajar-delete">Tolak</button>
                </form>
                @else
                <a href="{{ route('admin.pengajar.edit', $user->id) }}" class="btn-pengajar-edit">Edit</a>
                <form method="POST" action="{{ route('admin.pengajar.destroy', $user->id) }}"
                      onsubmit="return confirm('Hapus pengajar {{ $user->username }}?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="btn-pengajar-delete">Hapus</button>
                </form>
                @endif
            </div>
        </div>
        @empty
        <div class="pengajar-empty">Belum ada data pengajar.</div>
        @endforelse
    </div>

    @if ($pengajar->hasPages())
    <div class="table-pagination" style="padding: 1.25rem 1.5rem;">
        {{ $pengajar->withQueryString()->links() }}
    </div>
    @endif

</div>

@endsection
