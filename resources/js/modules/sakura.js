/**
 * sakura.js – Animasi kelopak sakura jatuh di hero (Canvas ringan)
 * Menggunakan requestAnimationFrame, aman untuk performa
 */

export function initSakura() {
    const canvas = document.getElementById('sakura-canvas');
    if (!canvas) return;

    // Hormati prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const ctx = canvas.getContext('2d');
    let W, H, petals;

    // ── Resize canvas sesuai hero ──────────────────────────────
    function resize() {
        W = canvas.width  = canvas.offsetWidth;
        H = canvas.height = canvas.offsetHeight;
    }

    window.addEventListener('resize', resize, { passive: true });
    resize();

    // ── Model kelopak ──────────────────────────────────────────
    const COLORS = [
        'rgba(255, 182, 193, 0.75)',  // pink muda
        'rgba(255, 148, 165, 0.65)',  // pink sedang
        'rgba(255, 105, 130, 0.50)',  // pink tua
        'rgba(247, 241, 227, 0.55)',  // krem (cherry blossom putih)
    ];

    function createPetal() {
        return {
            x:      Math.random() * W,
            y:      Math.random() * -H,           // Mulai di atas layar
            size:   Math.random() * 8 + 5,        // 5–13px
            speedX: (Math.random() - 0.5) * 1.2,  // Gerak horizontal acak
            speedY: Math.random() * 1.2 + 0.6,    // Jatuh pelan
            angle:  Math.random() * Math.PI * 2,  // Rotasi awal
            spin:   (Math.random() - 0.5) * 0.04, // Kecepatan putar
            color:  COLORS[Math.floor(Math.random() * COLORS.length)],
            sway:   Math.random() * 2,             // Amplitudo ayunan
            swaySpeed: Math.random() * 0.02 + 0.01,
            swayOffset: Math.random() * Math.PI * 2,
        };
    }

    // Buat 35 kelopak
    petals = Array.from({ length: 35 }, createPetal);

    // ── Gambar satu kelopak ────────────────────────────────────
    function drawPetal(p) {
        ctx.save();
        ctx.translate(p.x, p.y);
        ctx.rotate(p.angle);

        ctx.beginPath();
        // Bentuk daun oval sederhana
        ctx.ellipse(0, 0, p.size * 0.6, p.size, 0, 0, Math.PI * 2);
        ctx.fillStyle = p.color;
        ctx.fill();
        ctx.restore();
    }

    // ── Loop animasi ────────────────────────────────────────────
    let frame;
    let t = 0;

    function animate() {
        ctx.clearRect(0, 0, W, H);
        t += 0.016;

        petals.forEach(p => {
            // Ayunan horizontal sinus
            p.x += p.speedX + Math.sin(t * p.swaySpeed + p.swayOffset) * p.sway;
            p.y += p.speedY;
            p.angle += p.spin;

            drawPetal(p);

            // Kembalikan ke atas jika sudah keluar bawah / samping
            if (p.y > H + 20 || p.x < -30 || p.x > W + 30) {
                Object.assign(p, createPetal(), { x: Math.random() * W, y: -20 });
            }
        });

        frame = requestAnimationFrame(animate);
    }

    animate();

    // Hentikan saat halaman tidak terlihat (hemat baterai)
    document.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            cancelAnimationFrame(frame);
        } else {
            animate();
        }
    });
}
