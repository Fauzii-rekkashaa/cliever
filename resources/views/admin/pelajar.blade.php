@extends('layouts.admin')

@section('title', 'Data Pelajar - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">Data Pelajar</h1>
    <p class="page-subtitle">Kelola data pelajar yang terdaftar</p>
</div>

@if(session('success'))
<div class="alert-admin-success">✅ {{ session('success') }}</div>
@endif

<div class="table-card">

    <div class="table-search-wrap">
        <form method="GET" action="{{ route('admin.pelajar') }}">
            <div class="search-box">
                <svg class="search-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <circle cx="11" cy="11" r="8"/><line x1="21" y1="21" x2="16.65" y2="16.65"/>
                </svg>
                <input type="text" name="search" class="search-input"
                    placeholder="Cari Username atau email pelajar"
                    value="{{ request('search') }}" autocomplete="off">
            </div>
        </form>
    </div>

    <div class="table-wrap">
        <table class="data-table">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>Tanggal Bergabung</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($pelajar as $user)
                <tr>
                    <td class="td-username">{{ $user->username }}</td>
                    <td class="td-email">{{ $user->email }}</td>
                    <td><span class="badge-status badge-aktif">Aktif</span></td>
                    <td>{{ $user->created_at->format('j/n/Y') }}</td>
                    <td>
                        <div class="aksi-wrap">
                            <a href="{{ route('admin.pelajar.edit', $user->id) }}" class="btn-icon btn-edit" title="Edit">
                                <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                    <path d="M11 4H4a2 2 0 00-2 2v14a2 2 0 002 2h14a2 2 0 002-2v-7"/>
                                    <path d="M18.5 2.5a2.121 2.121 0 013 3L12 15l-4 1 1-4 9.5-9.5z"/>
                                </svg>
                            </a>
                            <form method="POST" action="{{ route('admin.pelajar.destroy', $user->id) }}"
                                  onsubmit="return confirm('Hapus pelajar {{ $user->username }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn-icon btn-delete" title="Hapus">
                                    <svg width="17" height="17" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <polyline points="3 6 5 6 21 6"/><path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6"/>
                                        <path d="M10 11v6M14 11v6"/><path d="M9 6V4a1 1 0 011-1h4a1 1 0 011 1v2"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="td-empty">Belum ada data pelajar.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if ($pelajar->hasPages())
    <div class="table-pagination">
        {{ $pelajar->withQueryString()->links() }}
    </div>
    @endif

</div>

@endsection
