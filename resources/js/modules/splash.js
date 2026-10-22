/**
 * splash.js – Modul splash screen NihonLink
 *
 * Urutan animasi (±3 detik total):
 *   0.0s  Lingkaran matahari merah membesar
 *   0.6s  Logo fade + scale + clip-path reveal
 *   1.8s  Garis loader merah mengisi (800ms)
 *   2.6s  Dua panel tirai bergeser ke kiri & kanan
 *   2.9s  Splash dihapus, konten utama muncul
 *
 * - Hanya tampil sekali per sesi (sessionStorage)
 * - Hormati prefers-reduced-motion (langsung fade singkat)
 * - Tombol "Lewati" tersedia setelah 0.8 detik
 * - Peta tidak di-render sebelum splash selesai (menggunakan Promise)
 */

// Fungsi gambar kelopak sakura ringan di canvas splash
function initSplashSakura(canvas) {
    const ctx = canvas.getContext('2d');
    canvas.width  = window.innerWidth;
    canvas.height = window.innerHeight;

    const petals = Array.from({ length: 18 }, () => ({
        x:    Math.random() * canvas.width,
        y:    Math.random() * canvas.height * 0.6 - canvas.height * 0.2,
        r:    Math.random() * 5 + 4,
        speed: Math.random() * 0.6 + 0.3,
        drift: Math.random() * 1.2 - 0.6,
        angle: Math.random() * Math.PI * 2,
        rotSpeed: Math.random() * 0.03 - 0.015,
        alpha: Math.random() * 0.5 + 0.3,
    }));

    let animId;

    function draw() {
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        petals.forEach(p => {
            ctx.save();
            ctx.globalAlpha = p.alpha;
            ctx.translate(p.x, p.y);
            ctx.rotate(p.angle);
            // Bentuk kelopak sederhana (elips miring)
            ctx.beginPath();
            ctx.ellipse(0, 0, p.r, p.r * 0.55, 0, 0, Math.PI * 2);
            ctx.fillStyle = '#e8a0b4';
            ctx.fill();
            ctx.restore();

            p.y    += p.speed;
            p.x    += p.drift;
            p.angle += p.rotSpeed;

            // Ulang dari atas saat keluar layar bawah
            if (p.y > canvas.height + 20) {
                p.y = -20;
                p.x = Math.random() * canvas.width;
            }
        });
        animId = requestAnimationFrame(draw);
    }

    draw();

    // Kembalikan fungsi stop agar bisa dihentikan saat splash tutup
    return () => cancelAnimationFrame(animId);
}

/**
 * Inisialisasi splash screen.
 * Mengembalikan Promise yang resolve saat splash selesai
 * agar map.js bisa menunggu sebelum render peta.
 */
export function initSplash() {
    return new Promise(resolve => {

        const splash = document.getElementById('splash-screen');
        if (!splash) { resolve(); return; }

        // --- Cek sessionStorage: jika sudah pernah tampil, langsung lewati ---
        if (sessionStorage.getItem('nihonlink_splash_shown')) {
            splash.remove();
            resolve();
            return;
        }

        // Tandai sudah tampil untuk sesi ini
        sessionStorage.setItem('nihonlink_splash_shown', '1');

        // Cek prefers-reduced-motion
        const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

        // Fungsi penutup splash (dipakai oleh tombol Lewati dan timer otomatis)
        function closeSplash(skipped = false) {
            stopSakura?.();
            splash.classList.add('splash--closing');
            // Tunggu animasi tirai selesai lalu hapus dari DOM
            splash.addEventListener('transitionend', () => {
                splash.remove();
                resolve(skipped);
            }, { once: true });

            // Fallback jika transitionend tidak terpicu
            setTimeout(() => {
                if (splash.parentNode) { splash.remove(); resolve(skipped); }
            }, 700);
        }

        // --- Prefers-reduced-motion: langsung tutup dengan fade singkat ---
        if (reduced) {
            splash.style.transition = 'opacity 0.35s ease';
            requestAnimationFrame(() => {
                splash.style.opacity = '0';
            });
            setTimeout(() => { splash.remove(); resolve(); }, 400);
            return;
        }

        // --- Animasi penuh ---

        // Mulai sakura
        const canvas   = document.getElementById('splash-sakura');
        let stopSakura = canvas ? initSplashSakura(canvas) : null;

        // Elemen animasi
        const sun    = splash.querySelector('.splash-sun');
        const logoWr = splash.querySelector('.splash-logo-wrap');
        const loader = splash.querySelector('.splash-loader');
        const bar    = splash.querySelector('.splash-loader__bar');
        const skip   = document.getElementById('splash-skip');

        // Fase 1 – matahari membesar (0 ms)
        setTimeout(() => {
            sun?.classList.add('splash-sun--grow');
        }, 50);

        // Fase 2 – logo muncul (600 ms)
        setTimeout(() => {
            logoWr?.classList.add('splash-logo-wrap--visible');
        }, 600);

        // Fase 3 – loading bar mengisi (1800 ms, durasi 800 ms)
        setTimeout(() => {
            loader?.classList.add('splash-loader--visible');
            bar?.classList.add('splash-loader__bar--fill');
        }, 1800);

        // Tombol Lewati tampil setelah 0.8 detik
        setTimeout(() => {
            skip?.classList.add('splash-skip--visible');
        }, 800);

        // Fase 4 – tutup (2700 ms)
        const autoClose = setTimeout(closeSplash, 2700);

        // Klik Lewati
        skip?.addEventListener('click', () => {
            clearTimeout(autoClose);
            closeSplash(true);
        });
    });
}
