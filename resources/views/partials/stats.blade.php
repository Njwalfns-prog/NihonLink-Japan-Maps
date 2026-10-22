{{--
    Stats Bar – Muncul di antara Hero dan Section Peta.
    Menampilkan total lokasi, kota, dan kategori dari database.
    Variabel: $totalPlaces, $totalCategories (dilewat dari welcome.blade.php)
--}}
<section class="stats-bar" aria-label="Statistik J-Map">
    <div class="stats-bar__inner">

        {{-- Stat: Total Lokasi --}}
        <div class="stats-bar__item" data-aos="fade-up" data-aos-delay="0">
            <span class="stats-bar__icon" aria-hidden="true">📍</span>
            <div class="stats-bar__content">
                <span
                    class="stats-bar__number"
                    data-count="{{ $totalPlaces ?? 120 }}"
                    data-suffix="+"
                >0</span>
                <span class="stats-bar__label">Lokasi Terdaftar</span>
            </div>
        </div>

        <div class="stats-bar__divider" aria-hidden="true"></div>

        {{-- Stat: Kategori --}}
        <div class="stats-bar__item" data-aos="fade-up" data-aos-delay="80">
            <span class="stats-bar__icon" aria-hidden="true">🎌</span>
            <div class="stats-bar__content">
                <span
                    class="stats-bar__number"
                    data-count="{{ $totalCategories ?? 5 }}"
                >0</span>
                <span class="stats-bar__label">Kategori</span>
            </div>
        </div>

        <div class="stats-bar__divider" aria-hidden="true"></div>

        {{-- Stat: Kota (static karena belum ada data city) --}}
        <div class="stats-bar__item" data-aos="fade-up" data-aos-delay="160">
            <span class="stats-bar__icon" aria-hidden="true">🏙️</span>
            <div class="stats-bar__content">
                <span class="stats-bar__number" data-count="18">0</span>
                <span class="stats-bar__label">Kota di Indonesia</span>
            </div>
        </div>

        <div class="stats-bar__divider" aria-hidden="true"></div>

        {{-- Stat: Komunitas aktif --}}
        <div class="stats-bar__item" data-aos="fade-up" data-aos-delay="240">
            <span class="stats-bar__icon" aria-hidden="true">👥</span>
            <div class="stats-bar__content">
                <span class="stats-bar__number" data-count="2400" data-suffix="+">0</span>
                <span class="stats-bar__label">Member Aktif</span>
            </div>
        </div>

    </div>
</section>
