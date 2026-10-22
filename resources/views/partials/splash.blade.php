{{--
    splash.blade.php – Splash screen NihonLink
    Tampil sekali per sesi (sessionStorage), sebelum halaman utama.
    Hormati prefers-reduced-motion.
    Urutan animasi:
        0.0s – lingkaran merah (matahari) membesar dari bawah logo
        0.6s – logo fade + scale + clip-path reveal dari bawah ke atas
        1.8s – garis loading tipis merah mengisi di bawah logo
        2.6s – dua panel (tirai torii) bergeser ke kiri & kanan
        2.9s – konten utama muncul
--}}
<div id="splash-screen" role="dialog" aria-modal="true" aria-label="Memuat NihonLink">

    {{-- Kelopak sakura ringan --}}
    <canvas id="splash-sakura" aria-hidden="true"></canvas>

    {{-- Panel tirai kiri (bergeser ke kiri saat selesai) --}}
    <div class="splash-panel splash-panel--left" aria-hidden="true"></div>

    {{-- Panel tirai kanan (bergeser ke kanan saat selesai) --}}
    <div class="splash-panel splash-panel--right" aria-hidden="true"></div>

    {{-- Konten tengah --}}
    <div class="splash-content" aria-hidden="true">

        {{-- Lingkaran matahari merah --}}
        <div class="splash-sun" aria-hidden="true"></div>

        {{-- Logo + nama --}}
        <div class="splash-logo-wrap">
            <img
                src="{{ asset('images/nihonlink-logo.png.png') }}"
                alt="NihonLink"
                class="splash-logo-img"
                width="120"
                height="120"
            >
            <p class="splash-logo-text">NihonLink</p>
            <p class="splash-logo-sub">Japanese Subculture Map</p>
        </div>

        {{-- Garis loading di bawah logo --}}
        <div class="splash-loader" aria-hidden="true">
            <div class="splash-loader__bar"></div>
        </div>

    </div>

    {{-- Tombol lewati (muncul setelah 0.8 detik) --}}
    <button
        id="splash-skip"
        class="splash-skip"
        aria-label="Lewati splash screen"
        type="button"
    >
        Lewati &rsaquo;
    </button>

</div>
