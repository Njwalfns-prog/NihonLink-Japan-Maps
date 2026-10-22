<x-app-layout>

{{-- ══════════════════════════════════════════════════════════════
     Splash Screen (hanya halaman utama, sekali per sesi)
     Partial terpisah agar mudah diatur oleh splash.js
═════════════════════════════════════════════════════════════════ --}}
@push('splash')
    @include('partials.splash')
    @if(false)
    @include('partials.gate')
    @endif
@endpush

{{-- Hitung data statistik dari database --}}
@php
    $totalPlaces     = $places->count();
    $totalCategories = $categories->count();
@endphp

{{--
    HERO LAMA – Disembunyikan dari halaman utama (peta jadi fokus pertama).
    TIDAK dihapus agar bisa diaktifkan kembali bila diperlukan.
    Uncomment blok ini untuk menampilkan kembali hero di atas peta.
--}}
{{--
@include('partials.hero')
@include('partials.stats', [
    'totalPlaces'     => $totalPlaces,
    'totalCategories' => $totalCategories,
])
--}}

{{-- ══════════════════════════════════════════════════════════════
     SECTION PETA UTAMA – Fullscreen 100vh dikurangi navbar
     Panel rekomendasi di SISI KANAN peta (desktop).
     Di HP, panel berubah menjadi bottom sheet.
═════════════════════════════════════════════════════════════════ --}}
<section id="peta" class="section-map-full" aria-label="Peta interaktif NihonLink">

    {{-- Layout: peta kiri + panel kanan --}}
    <div class="map-full-layout">

        {{-- ── Peta Leaflet (fokus utama) ─────────────────────── --}}
        <div class="map-full-container" role="application" aria-label="Peta interaktif NihonLink">
            <div id="map"></div>
        </div>

        {{-- ── Panel Rekomendasi KANAN (desktop sidebar / HP bottom sheet) ── --}}
        <aside
            id="map-sidebar"
            class="map-sidebar"
            aria-label="Panel rekomendasi tempat"
        >
            {{-- Handle bottom sheet (HP) --}}
            <div class="map-sidebar__handle" id="sidebar-handle" aria-hidden="true">
                <div class="map-sidebar__handle-bar"></div>
            </div>

            {{-- Header panel --}}
            <div class="map-sidebar__header">
                <h2 class="map-sidebar__title">
                    <span aria-hidden="true">🗾</span> Rekomendasi Tempat
                </h2>

                {{-- Search bar --}}
                <div class="search-wrapper">
                    <i class="fas fa-search search-icon" aria-hidden="true"></i>
                    <input
                        id="location-search"
                        type="search"
                        class="search-input"
                        placeholder="Cari nama, kota, deskripsi..."
                        aria-label="Cari lokasi"
                        autocomplete="off"
                    >
                </div>

                {{-- Chip filter kategori --}}
                <div class="filter-chips" role="group" aria-label="Filter kategori">
                    <button class="chip active" data-filter="semua" data-label="Semua" aria-pressed="true">
                        🗾 Semua
                    </button>
                    <button class="chip" data-filter="cosplay" data-label="Cosplay" aria-pressed="false">
                        🎭 Cosplay
                    </button>
                    <button class="chip" data-filter="event" data-label="Event" aria-pressed="false">
                        🎉 Event
                    </button>
                    <button class="chip" data-filter="komunitas" data-label="Komunitas" aria-pressed="false">
                        👥 Komunitas
                    </button>
                    <button class="chip" data-filter="merchandise" data-label="Merchandise" aria-pressed="false">
                        🛍️ Merchandise
                    </button>
                    <button class="chip" data-filter="anime" data-label="Anime" aria-pressed="false">
                        🎌 Anime
                    </button>

                    {{-- Kategori tambahan dari database --}}
                    @foreach($categories as $cat)
                        @php
                            $catSlug = strtolower(str_replace(' ', '_', $cat));
                            $known = ['cosplay','event','komunitas','merchandise','anime'];
                        @endphp
                        @if(!in_array($catSlug, $known))
                            <button class="chip" data-filter="{{ $catSlug }}" data-label="{{ ucfirst($cat) }}" aria-pressed="false">
                                📌 {{ ucfirst($cat) }}
                            </button>
                        @endif
                    @endforeach
                </div>
            </div>

            {{-- Daftar lokasi (diisi oleh map.js, skeleton saat loading) --}}
            <div
                id="location-list"
                class="map-sidebar__list"
                role="list"
                aria-label="Daftar lokasi"
                aria-live="polite"
            >
                {{-- Skeleton loading: tampil sebelum JS selesai render --}}
                @for($i = 0; $i < 4; $i++)
                <div class="skeleton-card" aria-hidden="true">
                    <div class="skeleton skeleton--thumb"></div>
                    <div class="skeleton-card__lines">
                        <div class="skeleton skeleton--tag"></div>
                        <div class="skeleton skeleton--title"></div>
                        <div class="skeleton skeleton--text"></div>
                        <div class="skeleton skeleton--text skeleton--text-short"></div>
                    </div>
                </div>
                @endfor
            </div>
        </aside>

    </div>
</section>

{{--
    SECTION KATEGORI LAMA – Dipindah ke bawah peta, masih tampil.
    Bagian kategori tetap ditampilkan di bawah peta.
--}}
<div class="seigaiha-divider" aria-hidden="true"></div>

<section id="kategori" class="section-categories">
    <div class="section-map__inner" style="padding-bottom: 0;">
        <div class="section-header" data-aos="fade-up">
            <span class="section-eyebrow">🎌 Kategori</span>
            <h2 class="section-title">
                Jelajahi Berdasarkan <span class="section-title-accent">Kategori</span>
            </h2>
            <p class="section-desc">
                Dari cosplay hingga komunitas lokal – temukan sesuai minatmu.
            </p>
        </div>
    </div>

    <div class="categories-grid">
        {{-- Card Cosplay --}}
        <div
            class="category-card"
            data-aos="fade-up"
            data-aos-delay="0"
            role="button"
            tabindex="0"
            onclick="filterByCategory('cosplay')"
            aria-label="Filter kategori Cosplay"
        >
            <span class="category-card__icon" aria-hidden="true">🎭</span>
            <p class="category-card__name">Cosplay</p>
            <p class="category-card__count">Studio, kontes &amp; komunitas</p>
        </div>

        {{-- Card Anime --}}
        <div
            class="category-card"
            data-aos="fade-up"
            data-aos-delay="80"
            role="button"
            tabindex="0"
            onclick="filterByCategory('anime')"
            aria-label="Filter kategori Anime"
        >
            <span class="category-card__icon" aria-hidden="true">🎌</span>
            <p class="category-card__name">Anime</p>
            <p class="category-card__count">Perpustakaan &amp; club</p>
        </div>

        {{-- Card Event --}}
        <div
            class="category-card"
            data-aos="fade-up"
            data-aos-delay="160"
            role="button"
            tabindex="0"
            onclick="filterByCategory('event')"
            aria-label="Filter kategori Event"
        >
            <span class="category-card__icon" aria-hidden="true">🎉</span>
            <p class="category-card__name">Event</p>
            <p class="category-card__count">Festival &amp; kompetisi</p>
        </div>

        {{-- Card Komunitas --}}
        <div
            class="category-card"
            data-aos="fade-up"
            data-aos-delay="240"
            role="button"
            tabindex="0"
            onclick="filterByCategory('komunitas')"
            aria-label="Filter kategori Komunitas"
        >
            <span class="category-card__icon" aria-hidden="true">👥</span>
            <p class="category-card__name">Komunitas</p>
            <p class="category-card__count">Gathering &amp; diskusi</p>
        </div>

        {{-- Card Merchandise --}}
        <div
            class="category-card"
            data-aos="fade-up"
            data-aos-delay="320"
            role="button"
            tabindex="0"
            onclick="filterByCategory('merchandise')"
            aria-label="Filter kategori Merchandise"
        >
            <span class="category-card__icon" aria-hidden="true">🛍️</span>
            <p class="category-card__name">Merchandise</p>
            <p class="category-card__count">Toko &amp; import langsung</p>
        </div>
    </div>
</section>

<div class="seigaiha-divider seigaiha-divider--dark" aria-hidden="true"></div>

{{-- ══════════════════════════════════════════════════════════════
     SECTION TENTANG (dipindah ke bawah, tetap tampil)
═════════════════════════════════════════════════════════════════ --}}
<section id="tentang" class="section-about">
    <div class="section-about__inner">

        {{-- Kiri: info proyek + CTA --}}
        <div class="section-about__content" data-aos="fade-right">
            <span class="section-eyebrow" style="color:var(--color-red-light)">⛩ Tentang NihonLink</span>
            <h2 class="section-title" style="color:var(--color-white)">
                Peta untuk<br>
                <span class="section-title-accent">Komunitas Jejepangan</span>
            </h2>
            <p style="color:rgba(255,255,255,.65);font-size:1rem;line-height:1.75;margin-bottom:var(--space-6)">
                NihonLink membantu penggemar subkultur Jepang menemukan lokasi-lokasi relevan
                di seluruh Indonesia — studio cosplay, toko merchandise, event anime, hingga
                komunitas lokal. Semua dalam satu peta interaktif.
            </p>

            {{-- Daftar fitur --}}
            <ul class="about-features" role="list">
                <li class="about-feature">
                    <span class="about-feature__icon" aria-hidden="true">🗾</span>
                    <div>
                        <strong>Peta Interaktif</strong>
                        <p>Marker kustom, popup detail, navigasi langsung ke Google Maps.</p>
                    </div>
                </li>
                <li class="about-feature">
                    <span class="about-feature__icon" aria-hidden="true">❤️</span>
                    <div>
                        <strong>Sistem Like</strong>
                        <p>Tandai lokasi favoritmu — status tersimpan di perangkatmu.</p>
                    </div>
                </li>
                <li class="about-feature">
                    <span class="about-feature__icon" aria-hidden="true">🔍</span>
                    <div>
                        <strong>Filter &amp; Pencarian</strong>
                        <p>Saring berdasarkan kategori atau nama secara real-time.</p>
                    </div>
                </li>
            </ul>

            {{-- CTA: guest hanya melihat peta dan login/daftar --}}
            @guest
            <div class="about-cta" data-aos="fade-up" data-aos-delay="120">
                <p class="about-cta__text">Daftarkan dirimu untuk memberi like dan ulasan!</p>
                <div class="about-cta__buttons">
                    <a href="{{ url('/register') }}" class="btn btn--primary btn--lg">
                        <i class="fas fa-user-plus" aria-hidden="true"></i> Daftar Sekarang
                    </a>
                    <a href="{{ url('/login') }}" class="btn btn--outline btn--lg">
                        Sudah punya akun? Masuk
                    </a>
                </div>
            </div>
            @endguest

            @auth
            <div class="about-cta">
                {{--
                    Tombol Dashboard hanya untuk semua user login.
                    Tombol Tambah Lokasi HANYA untuk admin (ikuti role yang sama
                    dengan navigation.blade.php: auth()->user()->role === 'admin').
                    TODO: Pastikan kolom `role` tersedia di model User.
                --}}
                <a href="{{ url('/dashboard') }}" class="btn btn--primary btn--lg">
                    <i class="fas fa-th-large" aria-hidden="true"></i> Dashboard
                </a>
                @if(auth()->user()->role === 'admin')
                <a href="{{ url('/places/create') }}" class="btn btn--outline btn--lg">
                    <i class="fas fa-plus" aria-hidden="true"></i> Tambah Lokasi
                </a>
                @endif
            </div>
            @endauth
        </div>

        {{-- Kanan: tim + tech stack --}}
        <div class="section-about__team" data-aos="fade-left" data-aos-delay="80">
            <h3 class="team-title">Tim Pengembang</h3>
            <div class="team-grid">
                <div class="team-card">
                    <span class="team-card__avatar" aria-hidden="true">🧑‍💻</span>
                    <div>
                        <p class="team-card__name">Frontend Dev</p>
                        <p class="team-card__role">UI/UX &amp; Animasi</p>
                    </div>
                </div>
                <div class="team-card">
                    <span class="team-card__avatar" aria-hidden="true">🧑‍💻</span>
                    <div>
                        <p class="team-card__name">Backend Dev</p>
                        <p class="team-card__role">API &amp; Database</p>
                    </div>
                </div>
                <div class="team-card">
                    <span class="team-card__avatar" aria-hidden="true">🎨</span>
                    <div>
                        <p class="team-card__name">UI/UX Designer</p>
                        <p class="team-card__role">Desain &amp; Riset</p>
                    </div>
                </div>
                <div class="team-card">
                    <span class="team-card__avatar" aria-hidden="true">📊</span>
                    <div>
                        <p class="team-card__name">Data &amp; Research</p>
                        <p class="team-card__role">Konten &amp; Validasi</p>
                    </div>
                </div>
            </div>

            {{-- Tech stack --}}
            <div class="tech-stack">
                <p class="tech-stack__label">Dibangun dengan</p>
                <div class="tech-stack__badges">
                    <span class="tech-badge">⚡ Laravel</span>
                    <span class="tech-badge">🗺️ Leaflet.js</span>
                    <span class="tech-badge">⚙️ Vite</span>
                    <span class="tech-badge">🎨 Tailwind</span>
                    <span class="tech-badge">🌍 OpenStreetMap</span>
                </div>
            </div>
        </div>

    </div>
</section>

{{-- Script: data dari backend + helper --}}
@push('scripts')
@php
    $mappedPlaces = $places->map(function ($p) {
        return [
            'id'          => $p->id,
            'nama'        => $p->name,
            'kategori'    => strtolower($p->category),
            'lat'         => (float) $p->latitude,
            'lng'         => (float) $p->longitude,
            'kota'        => 'Indonesia',   // TODO: tambahkan kolom city di model
            'deskripsi'   => $p->description ?? '',
            'jumlah_like' => 0,             // TODO: sambungkan ke tabel likes
            'image'       => $p->image ?? null,
            'rating'      => (float) ($p->reviews_avg_rating ?? 0),
            'review_count'=> (int) ($p->reviews_count ?? 0),
            'review_url'  => route('places.reviews.create', $p),
        ];
    })->values()->all();
@endphp
<script>
    /**
     * Data dari Laravel (places dari database).
     * Digunakan oleh map.js jika tersedia.
     */
    window.jmapPlaces = @json($mappedPlaces);

    /**
     * Helper global: klik card kategori → scroll ke peta + terapkan filter.
     * Digunakan oleh category-card di section bawah.
     */
    window.filterByCategory = function(cat) {
        const peta = document.getElementById('peta');
        if (peta) {
            window.scrollTo({ top: 0, behavior: 'smooth' }); // Peta di atas
        }
        // Aktifkan chip filter yang sesuai
        setTimeout(() => {
            const chip = document.querySelector(`.chip[data-filter="${cat}"]`);
            if (chip) chip.click();
        }, 400);
    };
</script>
@endpush

</x-app-layout>
