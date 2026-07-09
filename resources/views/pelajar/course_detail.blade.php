@extends('layouts.pelajar')

@section('title', $course->judul . ' - Cliever')

@section('content')

<a href="{{ route('pelajar.mycourse') }}" class="back-link">
    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
        <line x1="19" y1="12" x2="5" y2="12"/><polyline points="12 19 5 12 12 5"/>
    </svg>
    Back to My Course
</a>

<div class="course-detail-layout">

    <div class="course-detail-main">

        <div class="course-detail-thumbnail">
            @if($course->thumbnail)
                <img src="{{ asset('storage/' . $course->thumbnail) }}" alt="{{ $course->judul }}">
            @endif
        </div>

        <h1 class="course-detail-judul">{{ $course->judul }}</h1>
        <p class="continue-by" style="margin-bottom:1rem;">by {{ $course->pengajar->nama ?? $course->pengajar->username }}</p>
        <p class="course-detail-desc">{{ $course->deskripsi }}</p>

        {{-- Tab info --}}
        <div class="course-tab-info">
            <span class="course-tab-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg>
                Materi Pembelajaran <strong>{{ $totalMateri }}</strong>
            </span>
            <span class="course-tab-item">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M21 15a2 2 0 01-2 2H7l-4 4V5a2 2 0 012-2h14a2 2 0 012 2z"/></svg>
                Diskusi &amp; Ulasan <strong>{{ $course->reviews->count() }}</strong>
            </span>
        </div>

        @if(session('success'))
        <div class="alert-admin-success" style="margin-top:1rem;">✅ {{ session('success') }}</div>
        @endif

        {{-- Rating Summary --}}
        @php
            $reviews      = $course->reviews()->with('user')->latest()->get();
            $avgRating    = $reviews->avg('rating') ?? 0;
            $totalReviews = $reviews->count();
            $distribution = [];
            for ($i = 5; $i >= 1; $i--) {
                $distribution[$i] = $reviews->where('rating', $i)->count();
            }
        @endphp

        <div class="rating-summary-card">
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
        </div>

        {{-- Form Tulis Ulasan --}}
        <div class="review-form-card">
            <h3 class="review-form-title">Tulis Ulasanmu</h3>

            @if($existingReview)
            <div style="background:#eff6ff; border:1.5px solid #bfdbfe; border-radius:10px; padding:.75rem 1rem; margin-bottom:1rem; font-size:.85rem; color:#1d4ed8;">
                ✏️ Kamu sudah pernah memberikan ulasan. Form di bawah akan mengubah ulasanmu sebelumnya.
            </div>
            @endif

            <form method="POST" action="{{ route('pelajar.review.submit', $course->id) }}">
                @csrf
                <div class="review-star-input">
                    <label class="form-label" style="margin-bottom:.5rem;">Penilaian</label>
                    <div class="star-picker" id="starPicker">
                        @for($i = 1; $i <= 5; $i++)
                        <svg class="star-pick {{ $existingReview && $existingReview->rating >= $i ? 'active' : '' }}"
                             data-value="{{ $i }}" width="32" height="32" viewBox="0 0 24 24"
                             fill="{{ $existingReview && $existingReview->rating >= $i ? '#f59e0b' : 'none' }}"
                             stroke="#f59e0b" stroke-width="2" style="cursor:pointer;">
                            <polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/>
                        </svg>
                        @endfor
                        <input type="hidden" name="rating" id="ratingInput" value="{{ $existingReview->rating ?? '' }}" required>
                    </div>
                    @error('rating')
                    <p style="color:#ef4444; font-size:.8rem; margin-top:.25rem;">{{ $message }}</p>
                    @enderror
                </div>

                <div class="form-group form-group--full" style="margin-top:1rem;">
                    <textarea name="komentar" class="form-textarea" rows="4"
                        placeholder="Bagikan pengalaman belajarmu disini..."
                        style="background:var(--gray-50);">{{ $existingReview->komentar ?? old('komentar') }}</textarea>
                    <p style="font-size:.75rem; color:var(--gray-400); margin-top:.25rem;">Minimal 10 karakter</p>
                </div>

                <div style="display:flex; justify-content:flex-end; margin-top:.75rem;">
                    <button type="submit" class="btn-form-submit">Kirim Ulasan</button>
                </div>
            </form>
        </div>

        {{-- Daftar Ulasan --}}
        <div style="margin-top:1.5rem;">
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
                            </div>
                        </div>
                    </div>
                    @if($review->komentar)
                    <p class="review-komentar">{{ $review->komentar }}</p>
                    @endif
                </div>
            </div>
            @empty
            <p style="color:var(--gray-400); font-style:italic; text-align:center; padding:2rem 0;">
                Belum ada ulasan untuk course ini.
            </p>
            @endforelse
        </div>

        {{-- Course Content --}}
        <div class="materi-card" style="margin-top:2rem;">
            <div class="materi-card-header">
                <h2 class="materi-card-title">Course Content</h2>
            </div>
            <div class="materi-list-wrap">
                @forelse($materi as $index => $item)
                @php
                    $isSelesai = $progressMap[$item->id] ?? false;
                    $isLocked  = $lockedMap[$item->id] ?? false;
                @endphp
                @if($isLocked)
                <div class="materi-row materi-row-locked">
                    <div class="materi-check-btn materi-check-locked">
                        <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/></svg>
                    </div>
                    <div class="materi-row-info">
                        <p class="materi-row-judul" style="color:var(--gray-400);">{{ $item->judul }}</p>
                        <span class="materi-meta-item">Selesaikan materi sebelumnya untuk membuka</span>
                    </div>
                </div>
                @else
                <a href="{{ route('pelajar.materi.show', $item->id) }}" class="materi-row materi-row-clickable" style="text-decoration:none; color:inherit;">
                    <div class="materi-check-btn {{ $isSelesai ? 'materi-check-done' : '' }}">
                        @if($isSelesai)
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><polyline points="20 6 9 17 4 12"/></svg>
                        @else
                        {{ $index + 1 }}
                        @endif
                    </div>
                    <div class="materi-row-info">
                        <p class="materi-row-judul">{{ $item->judul }}</p>
                        <div class="materi-row-meta">
                            @if($item->tipe === 'video')
                                <span class="materi-meta-item"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polygon points="23 7 16 12 23 17 23 7"/><rect x="1" y="5" width="15" height="14" rx="2" ry="2"/></svg> Video</span>
                            @elseif($item->tipe === 'file')
                                <span class="materi-meta-item"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"/><polyline points="14 2 14 8 20 8"/></svg> File</span>
                            @else
                                <span class="materi-meta-item"><svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="21" y1="10" x2="3" y2="10"/><line x1="21" y1="6" x2="3" y2="6"/><line x1="21" y1="14" x2="3" y2="14"/><line x1="21" y1="18" x2="3" y2="18"/></svg> Teks</span>
                            @endif
                        </div>
                    </div>
                </a>
                @endif
                @empty
                <div style="text-align:center; padding:3rem; color:#94a3b8; font-style:italic;">Belum ada materi.</div>
                @endforelse
            </div>
        </div>

    </div>

    {{-- Sidebar Progress --}}
    <div class="course-detail-sidebar">
        <div class="progress-panel">
            <div class="progress-panel-header">
                <span>Your Progress</span>
                <span class="progress-percent">{{ $enrollment->progress ?? 0 }}%</span>
            </div>
            <div class="progress-bar-track" style="margin-bottom:.75rem;">
                <div class="progress-bar-fill" style="width: {{ $enrollment->progress ?? 0 }}%"></div>
            </div>
            <p class="progress-panel-sub">{{ $selesaiCount }} of {{ $totalMateri }} lessons completed</p>

            @php
                $nextUnlocked = $materi->first(fn($m) => !($lockedMap[$m->id] ?? false) && !($progressMap[$m->id] ?? false));
            @endphp
            @if($nextUnlocked)
            <a href="{{ route('pelajar.materi.show', $nextUnlocked->id) }}" class="btn-continue-learning" style="display:block; text-align:center; text-decoration:none;">
                Continue Learning
            </a>
            @endif
        </div>
    </div>

</div>

@endsection

@push('scripts')
<script>
// Star picker interactif
const stars = document.querySelectorAll('.star-pick');
const ratingInput = document.getElementById('ratingInput');

stars.forEach(star => {
    star.addEventListener('mouseover', function() {
        const val = parseInt(this.dataset.value);
        stars.forEach((s, i) => {
            s.setAttribute('fill', i < val ? '#f59e0b' : 'none');
        });
    });

    star.addEventListener('mouseout', function() {
        const selected = parseInt(ratingInput.value) || 0;
        stars.forEach((s, i) => {
            s.setAttribute('fill', i < selected ? '#f59e0b' : 'none');
        });
    });

    star.addEventListener('click', function() {
        const val = parseInt(this.dataset.value);
        ratingInput.value = val;
        stars.forEach((s, i) => {
            s.setAttribute('fill', i < val ? '#f59e0b' : 'none');
        });
    });
});
</script>
@endpush
