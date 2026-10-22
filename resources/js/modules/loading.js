/**
 * loading.js – Animasi loading screen singkat
 * Menyembunyikan loading screen setelah halaman siap
 */

export function initLoadingScreen() {
    const screen = document.getElementById('loading-screen');
    if (!screen) return;

    // Sembunyikan setelah semua aset terpasang (min 800ms agar animasi terlihat)
    const minDelay = 800;
    const start = Date.now();

    window.addEventListener('load', () => {
        const elapsed = Date.now() - start;
        const remaining = Math.max(0, minDelay - elapsed);

        setTimeout(() => {
            screen.classList.add('hidden');
            // Hapus dari DOM setelah animasi selesai
            screen.addEventListener('transitionend', () => screen.remove(), { once: true });
        }, remaining);
    });
}
