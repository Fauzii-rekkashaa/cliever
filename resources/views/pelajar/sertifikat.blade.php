@extends('layouts.pelajar')

@section('title', 'My Certificates - Cliever')

@section('content')

<div class="page-header">
    <h1 class="page-title">My Certificates</h1>
    <p class="page-subtitle">View and download your earned certificates</p>
</div>

<div class="sertifikat-grid">
    @forelse($sertifikat as $enrollment)
    <div class="sertifikat-card">

        {{-- Header banner --}}
        <div class="sertifikat-banner">
            <svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="#fff" stroke-width="1.5">
                <circle cx="12" cy="8" r="6"/><path d="M9 14l-2 8 5-3 5 3-2-8"/>
            </svg>
            <h2 class="sertifikat-title">Sertifikat Penyelesaian</h2>
            <p class="sertifikat-sub">Dengan ini menyatakan bahwa</p>
            <p class="sertifikat-nama">{{ Auth::user()->nama ?? Auth::user()->username }}</p>
            <p class="sertifikat-sub">telah berhasil diselesaikan</p>
            <p class="sertifikat-course">{{ $enrollment->course->judul }}</p>
        </div>

        {{-- Detail --}}
        <div class="sertifikat-detail">
            <div class="sertifikat-row">
                <span>Pengajar</span>
                <strong>{{ $enrollment->course->pengajar->nama ?? $enrollment->course->pengajar->username }}</strong>
            </div>
            <div class="sertifikat-row">
                <span>Selesai</span>
                <strong>{{ \Carbon\Carbon::parse($enrollment->sertifikat->tanggal_terbit ?? $enrollment->updated_at)->translatedFormat('d F Y') }}</strong>
            </div>
            <div class="sertifikat-row">
                <span>Certificate ID</span>
                <strong>CERT-{{ \Carbon\Carbon::parse($enrollment->sertifikat->tanggal_terbit ?? $enrollment->updated_at)->format('Y') }}-{{ str_pad($enrollment->id, 6, '0', STR_PAD_LEFT) }}</strong>
            </div>

            <a href="{{ route('pelajar.sertifikat.download', $enrollment->id) }}" class="btn-download-sertifikat">
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"/><polyline points="7 10 12 15 17 10"/><line x1="12" y1="15" x2="12" y2="3"/></svg>
                Download
            </a>
        </div>

    </div>
    @empty
    <div style="text-align:center; padding:3rem; color:var(--gray-400); font-style:italic;">
        Belum ada sertifikat. Selesaikan course untuk mendapatkan sertifikat!
    </div>
    @endforelse
</div>

@endsection
