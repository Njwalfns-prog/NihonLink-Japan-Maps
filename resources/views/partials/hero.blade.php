{{-- Hero Section: ±45% tinggi layar, sakura jatuh, tombol scroll ke peta --}}
<section id="hero" class="hero" aria-label="Beranda J-Map">

    {{-- Canvas animasi sakura (ringan, CSS + JS sederhana) --}}
    <canvas id="sakura-canvas" class="hero__sakura-canvas" aria-hidden="true"></canvas>

    {{-- Overlay gradient --}}
    <div class="hero__overlay" aria-hidden="true"></div>

    {{-- Konten teks --}}
    <div class="hero__content">
        <p class="hero__eyebrow" data-aos="fade-down" data-aos-delay="100">
            <span class="hero__eyebrow-icon">⛩</span> Japanese Subculture Map
        </p>

        <h1 class="hero__title" data-aos="fade-up" data-aos-delay="200">
            Temukan Dunia<br>
            <span class="hero__title-accent">Jepang</span> di Indonesia
        </h1>

        <p class="hero__tagline" data-aos="fade-up" data-aos-delay="350">
            Peta interaktif komunitas cosplay, event anime, toko merchandise,<br class="d-none d-md-block">
            dan semua hal berbau Jepang di seluruh Nusantara.
        </p>

        {{-- Statistik kecil --}}
        <div class="hero__stats" data-aos="fade-up" data-aos-delay="450">
            <div class="hero__stat">
                <span class="hero__stat-number" data-count="120">0</span>
                <span class="hero__stat-label">Lokasi</span>
            </div>
            <div class="hero__stat-divider" aria-hidden="true">|</div>
            <div class="hero__stat">
                <span class="hero__stat-number" data-count="18">0</span>
                <span class="hero__stat-label">Kota</span>
            </div>
            <div class="hero__stat-divider" aria-hidden="true">|</div>
            <div class="hero__stat">
                <span class="hero__stat-number" data-count="5">0</span>
                <span class="hero__stat-label">Kategori</span>
            </div>
        </div>

        {{-- CTA --}}
        <div class="hero__cta" data-aos="fade-up" data-aos-delay="550">
            <a href="#peta" id="btn-mulai-jelajah" class="btn btn--primary btn--lg hero__btn-main">
                <i class="fas fa-map-marked-alt" aria-hidden="true"></i>
                Mulai Jelajah
            </a>
            <a href="#kategori" class="btn btn--outline btn--lg hero__btn-secondary">
                <i class="fas fa-th-large" aria-hidden="true"></i>
                Lihat Kategori
            </a>
        </div>
    </div>

    {{-- Indikator scroll --}}
    <div class="hero__scroll-indicator" aria-hidden="true">
        <span class="hero__scroll-text">Gulir ke bawah</span>
        <div class="hero__scroll-arrow">
            <i class="fas fa-chevron-down"></i>
        </div>
    </div>
</section>

{{-- Pembatas motif seigaiha --}}
<div class="seigaiha-divider" aria-hidden="true"></div>
