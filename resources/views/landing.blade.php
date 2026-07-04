@extends('layouts.app')

@section('title', 'Cliever - Belajar Nggak Harus Ribet')

@section('content')

{{-- ===== NAVBAR ===== --}}
<nav class="navbar">
    <div class="navbar-container">
        <a href="{{ route('home') }}" class="navbar-brand">
            <span class="brand-icon">✦</span>
            Cliever
        </a>
        <ul class="navbar-menu">
            <li><a href="{{ route('home') }}" class="nav-link active">Home</a></li>
            <li><a href="#fitur" class="nav-link">Fitur</a></li>
            <li><a href="#tentang" class="nav-link">Tentang</a></li>
        </ul>
        <div class="navbar-actions">
            <a href="{{ route('login') }}" class="btn-nav-login">Login</a>
            <a href="{{ route('register') }}" class="btn-nav-daftar">Daftar</a>
        </div>

        {{-- Mobile hamburger --}}
        <button class="hamburger" id="hamburgerBtn" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>

    {{-- Mobile Menu --}}
    <div class="mobile-menu" id="mobileMenu">
        <a href="{{ route('home') }}">Home</a>
        <a href="#fitur">Fitur</a>
        <a href="#tentang">Tentang</a>
        <a href="{{ route('login') }}">Login</a>
        <a href="{{ route('register') }}">Daftar</a>
    </div>
</nav>

{{-- ===== HERO SECTION ===== --}}
<section class="hero">
    <div class="hero-container">
        {{-- Left: Text --}}
        <div class="hero-content" data-animate>
            <div class="hero-badge">🎓 Platform Belajar #1</div>
            <h1 class="hero-title">
                Belajar Nggak Harus <span class="text-gradient">Ribet</span>
            </h1>
            <p class="hero-desc">
                Nikmati pengalaman belajar interaktif yang bikin kamu betah dan makin paham
            </p>
            <div class="hero-buttons">
                <a href="{{ route('register') }}" class="btn-primary">
                    <span>Daftar</span>
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
                <a href="{{ route('login') }}" class="btn-outline">Login</a>
            </div>

            {{-- Stats --}}
            <div class="hero-stats">
                <div class="stat-item">
                    <span class="stat-number">10K+</span>
                    <span class="stat-label">Pelajar</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number">200+</span>
                    <span class="stat-label">Kursus</span>
                </div>
                <div class="stat-divider"></div>
                <div class="stat-item">
                    <span class="stat-number">50+</span>
                    <span class="stat-label">Pengajar</span>
                </div>
            </div>
        </div>

        {{-- Right: Image --}}
        <div class="hero-image-wrapper" data-animate data-delay="200">
            <div class="image-glow"></div>
            <div class="hero-image-card">
                <img
                    src="https://images.unsplash.com/photo-1522202176988-66273c2fd55f?w=700&q=80"
                    alt="Belajar Online"
                    class="hero-img"
                    loading="eager"
                >
                {{-- Floating badge --}}
                <div class="floating-badge badge-top">
                    <span class="badge-icon">🔥</span>
                    <div>
                        <div class="badge-title">Trending</div>
                        <div class="badge-sub">Web Development</div>
                    </div>
                </div>
                <div class="floating-badge badge-bottom">
                    <span class="badge-icon">⭐</span>
                    <div>
                        <div class="badge-title">4.9 Rating</div>
                        <div class="badge-sub">dari 2.400 ulasan</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Background decorations --}}
    <div class="hero-bg-orb orb-1"></div>
    <div class="hero-bg-orb orb-2"></div>
</section>

{{-- ===== FITUR SECTION ===== --}}
<section class="fitur-section" id="fitur">
    <div class="section-container">
        <div class="section-header" data-animate>
            <span class="section-eyebrow">Kenapa Cliever?</span>
            <h2 class="section-title">Semua yang Kamu Butuhkan Ada Di Sini</h2>
        </div>

        <div class="fitur-grid">
            @foreach([
                ['icon' => '🎯', 'title' => 'Belajar Terarah', 'desc' => 'Kurikulum terstruktur dari nol sampai mahir, dirancang bareng para ahli.'],
                ['icon' => '⚡', 'title' => 'Akses Fleksibel', 'desc' => 'Belajar kapan saja, di mana saja. Sesuaikan dengan jadwal kamu.'],
                ['icon' => '🏆', 'title' => 'Sertifikat Otomatis', 'desc' => 'Dapatkan sertifikat digital begitu kamu menyelesaikan semua materi dalam sebuah course.'],
                ['icon' => '📊', 'title' => 'Materi Berurutan', 'desc' => 'Belajar step-by-step dengan materi terkunci otomatis sampai kamu menyelesaikan tahap sebelumnya.'],
            ] as $fitur)
            <div class="fitur-card" data-animate>
                <div class="fitur-icon">{{ $fitur['icon'] }}</div>
                <h3 class="fitur-title">{{ $fitur['title'] }}</h3>
                <p class="fitur-desc">{{ $fitur['desc'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ===== CTA SECTION ===== --}}
<section class="cta-section" id="tentang">
    <div class="cta-container" data-animate>
        <div class="cta-glow"></div>
        <span class="cta-eyebrow">Mulai Sekarang</span>
        <h2 class="cta-title">Kuasai Keahlian Baru<br>Mulai Hari Ini!</h2>
        <p class="cta-desc">
            Ribuan orang sudah mulai meningkatkan kapasitas diri mereka.<br>
            Sekarang giliran kamu
        </p>
        <a href="{{ route('register') }}" class="btn-cta">
            Daftar Sekarang
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
        </a>
    </div>
</section>

{{-- ===== FOOTER ===== --}}
<footer class="footer">
    <div class="footer-container">
        <div class="footer-brand">
            <span class="brand-icon">✦</span> Cliever
        </div>
        <p class="footer-copy">&copy; {{ date('Y') }} Cliever. Semua hak dilindungi.</p>
    </div>
</footer>

@endsection

@push('scripts')
<script>
// Intersection Observer untuk animasi scroll
const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            const delay = entry.target.dataset.delay || 0;
            setTimeout(() => entry.target.classList.add('animate-in'), delay);
            observer.unobserve(entry.target);
        }
    });
}, { threshold: 0.1 });

document.querySelectorAll('[data-animate]').forEach(el => observer.observe(el));

// Mobile hamburger toggle
const hamburger = document.getElementById('hamburgerBtn');
const mobileMenu = document.getElementById('mobileMenu');
hamburger.addEventListener('click', () => {
    hamburger.classList.toggle('open');
    mobileMenu.classList.toggle('open');
});

// Navbar scroll effect
window.addEventListener('scroll', () => {
    const navbar = document.querySelector('.navbar');
    navbar.classList.toggle('scrolled', window.scrollY > 30);
});
</script>
@endpush
