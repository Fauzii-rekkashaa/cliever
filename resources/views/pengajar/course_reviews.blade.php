@extends('layouts.pengajar')

@section('title', 'Ulasan - ' . $course->judul . ' - Cliever')

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

{{-- Rating Summary --}}
<div class="rating-summary-card" style="margin-top:1.5rem;">
    <div class="rating-big-score">
        <span class="rating-number">{{ number_format($avgRating, 1) }}</span>
        <div class="rating-stars-display">
            @for($i = 1; $i <= 5; $i++)
            <svg width="22" height="22" viewBox="0 0 24 24"
                fill="{{ $i <= round($avgRating) ? '#f59e0b' : 'none' }}"
                stroke="#f59e0b" stroke-width="2">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            @endfor
        </div>
        <p style="font-size:.8rem; color:var(--gray-400);">{{ $totalReviews }} ulasan</p>
    </div>

    <div class="rating-bars">
        @for($i = 5; $i >= 1; $i--)
        @php $count = $distribution[$i] ?? 0; $pct = $totalReviews > 0 ? ($count/$totalReviews)*100 : 0; @endphp
        <div class="rating-bar-row">
            <span class="rating-bar-label">{{ $i }}</span>
            <svg width="13" height="13" viewBox="0 0 24 24" fill="#f59e0b" stroke="#f59e0b" stroke-width="1">
                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
            </svg>
            <div class="rating-bar-track-outer">
                <div class="rating-bar-track-fill" style="width:{{ $pct }}%"></div>
            </div>
            <span class="rating-bar-count">{{ $count }}</span>
        </div>
        @endfor
    </div>

    {{-- Statistik singkat --}}
    <div style="flex-shrink:0; text-align:center; padding:0 1rem; border-left:1.5px solid var(--gray-100);">
        @php
            $positif = $reviews->where('rating', '>=', 4)->count();
            $pctPositif = $totalReviews > 0 ? round(($positif/$totalReviews)*100) : 0;
        @endphp
        <p style="font-size:.8rem; color:var(--gray-400); margin-bottom:.5rem;">Tingkat Kepuasan</p>
        <p style="font-family:var(--font-main); font-size:1.75rem; font-weight:800; color:#22c55e;">{{ $pctPositif }}%</p>
        <p style="font-size:.75rem; color:var(--gray-400);">pelajar puas</p>
    </div>
</div>

{{-- Filter Badge --}}
<div style="display:flex; gap:.75rem; margin:1.5rem 0; flex-wrap:wrap;">
    <span style="display:inline-flex; align-items:center; gap:.35rem; background:#f0fdf4; border:1.5px solid #bbf7d0; color:#16a34a; border-radius:20px; padding:.3rem .9rem; font-size:.82rem; font-weight:600;">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="23 6 13.5 15.5 8.5 10.5 1 18"/></svg>
        {{ $positif }} positif ({{ $pctPositif }}%)
    </span>
    <span style="display:inline-flex; align-items:center; gap:.35rem; background:var(--gray-50); border:1.5px solid var(--gray-200); color:var(--gray-600); border-radius:20px; padding:.3rem .9rem; font-size:.82rem; font-weight:600;">
        {{ $totalReviews - $positif }} perlu perhatian
    </span>
</div>

{{-- List Ulasan --}}
<div class="materi-card">
    <div class="materi-card-header">
        <div>
            <h2 class="materi-card-title">Semua Ulasan</h2>
            <p class="materi-card-count">{{ $totalReviews }} ulasan dari pelajar</p>
        </div>
    </div>

    <div style="padding:0 1.75rem;">
        @forelse($reviews as $review)
        <div class="review-item">
            <div class="review-avatar">
                {{ strtoupper(substr($review->user->nama ?? $review->user->username, 0, 1)) }}
            </div>
            <div class="review-content">
                <div class="review-header">
                    <div>
                        <span class="review-name">{{ $review->user->nama ?? $review->user->username }}</span>
                        <div class="review-stars-small">
                            @for($i = 1; $i <= 5; $i++)
                            <svg width="13" height="13" viewBox="0 0 24 24"
                                fill="{{ $i <= $review->rating ? '#f59e0b' : 'none' }}"
                                stroke="#f59e0b" stroke-width="2">
                                <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                            </svg>
                            @endfor
                            <span style="font-size:.75rem; color:var(--gray-400); margin-left:.25rem;">
                                {{ $review->created_at->diffForHumans() }}
                            </span>
                            {{-- Badge positif/negatif --}}
                            @if($review->rating >= 4)
                            <span style="margin-left:.5rem; background:#f0fdf4; color:#16a34a; border:1px solid #bbf7d0; border-radius:20px; padding:.1rem .6rem; font-size:.72rem; font-weight:600;">Positif</span>
                            @elseif($review->rating >= 3)
                            <span style="margin-left:.5rem; background:#fffbeb; color:#d97706; border:1px solid #fde68a; border-radius:20px; padding:.1rem .6rem; font-size:.72rem; font-weight:600;">Netral</span>
                            @else
                            <span style="margin-left:.5rem; background:#fef2f2; color:#dc2626; border:1px solid #fecaca; border-radius:20px; padding:.1rem .6rem; font-size:.72rem; font-weight:600;">Perlu Perhatian</span>
                            @endif
                        </div>
                    </div>
                </div>
                @if($review->komentar)
                <p class="review-komentar">{{ $review->komentar }}</p>
                @else
                <p style="font-size:.82rem; color:var(--gray-400); font-style:italic; margin-top:.35rem;">Tidak ada komentar.</p>
                @endif
            </div>
        </div>
        @empty
        <div style="text-align:center; padding:3rem; color:var(--gray-400); font-style:italic;">
            Belum ada ulasan untuk course ini.
        </div>
        @endforelse
    </div>
</div>

@endsection
