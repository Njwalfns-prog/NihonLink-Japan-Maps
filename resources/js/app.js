/**
 * app.js – Entry point utama NihonLink
 * Mengimpor semua modul dan menginisialisasi setelah DOM siap.
 * Peta hanya di-render setelah splash selesai agar tidak patah-patah.
 */

// ── Library eksternal ─────────────────────────────────────────
import AOS from 'aos';
import 'aos/dist/aos.css';

// ── Modul NihonLink ───────────────────────────────────────────
import { initSplash }      from './modules/splash.js';
import { initLoadingScreen } from './modules/loading.js';
import { initNavbar }      from './modules/navbar.js';
import { initSakura }      from './modules/sakura.js';
import { initCounters }    from './modules/counter.js';
import { initMap }          from './modules/map.js';
import { runMapIntro }      from './modules/map-intro.js';
import { initUI }          from './modules/ui.js';
import { initDisclaimer }  from './modules/disclaimer.js';

// ── Inisialisasi setelah DOM siap ─────────────────────────────
document.addEventListener('DOMContentLoaded', async () => {

    // ── Splash screen (hanya di halaman utama yang ada #splash-screen) ──
    // Menunggu splash selesai sebelum render peta agar tidak patah-patah
    const splashEl = document.getElementById('splash-screen');
    const introEligible = Boolean(
        splashEl
        && !sessionStorage.getItem('nihonlink_splash_shown')
        && !sessionStorage.getItem('nihonlink_map_intro_shown')
        && !window.matchMedia('(prefers-reduced-motion: reduce)').matches
    );
    if (splashEl) {
        await initSplash();
    } else {
        // Halaman lain: tampilkan loading screen singkat biasa
        initLoadingScreen();
    }

    // Navbar sticky + hamburger + smooth scroll + avatar dropdown
    initNavbar();

    // Fitur UI umum (back-to-top, chip aria, bottom sheet drag)
    initUI();

    // Animasi kelopak sakura (hanya di hero jika ada canvas)
    initSakura();

    // AOS scroll reveal (hanya sekali)
    AOS.init({
        once:     true,
        duration: 350,
        easing:   'ease-out-cubic',
        offset:   60,
    });

    // Counter statistik (hero + stats bar)
    initCounters();

    // Peta Leaflet
    // Jika ada data dari server (via window.jmapPlaces di welcome.blade.php),
    // gunakan data tersebut; jika tidak, gunakan data dummy 8 lokasi
    const serverPlaces = window.jmapPlaces || null;
    const map = initMap(serverPlaces, { deferInitialBounds: introEligible });

    // Intro dimulai setelah splash selesai dan marker peta sudah dibuat.
    if (introEligible && map) {
        map.invalidateSize({ pan: false });
        await runMapIntro();
    }

    // Banner disclaimer baru tampil setelah intro peta selesai.
    initDisclaimer();

});
