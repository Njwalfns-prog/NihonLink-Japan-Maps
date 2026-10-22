{{--
    disclaimer.blade.php – Banner disclaimer NihonLink
    Tampil sekali per sesi (sessionStorage via JS).
    Posisi: tepat di bawah navbar, dismissible dengan tombol ×.
    JS: modules/disclaimer.js
--}}
<div
    id="disclaimer-banner"
    class="disclaimer-banner"
    hidden
    role="complementary"
    aria-label="Informasi layanan NihonLink"
    aria-live="polite"
>
    <div class="disclaimer-banner__inner">

        {{-- Ikon info --}}
        <span class="disclaimer-banner__icon" aria-hidden="true">
            <i class="fas fa-info-circle"></i>
        </span>

        {{-- Teks --}}
        <p class="disclaimer-banner__text">
            <strong>NihonLink</strong> adalah wadah informasi subkultur Jepang.
            Navigasi dan rute ke lokasi dibuka melalui
            <span class="disclaimer-banner__gmaps">
                <i class="fab fa-google" aria-hidden="true"></i> Google Maps
            </span>.
        </p>

        {{-- Tombol dismiss --}}
        <button
            id="disclaimer-close"
            class="disclaimer-banner__close"
            aria-label="Tutup pemberitahuan"
            type="button"
        >
            <i class="fas fa-times" aria-hidden="true"></i>
        </button>

    </div>
</div>
