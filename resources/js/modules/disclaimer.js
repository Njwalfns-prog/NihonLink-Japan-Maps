/**
 * disclaimer.js – Logika banner disclaimer NihonLink
 *
 * - Tampil sekali per sesi (sessionStorage 'nihonlink_disclaimer_shown')
 * - Tombol × menutup banner dengan animasi slide-up
 * - Hormati prefers-reduced-motion
 */

export function initDisclaimer() {
    const banner = document.getElementById('disclaimer-banner');
    const closeBtn = document.getElementById('disclaimer-close');
    if (!banner) return;

    // Jika sudah pernah ditampilkan sesi ini, langsung sembunyikan
    if (sessionStorage.getItem('nihonlink_disclaimer_shown')) {
        return;
    }

    // Tandai sudah tampil
    sessionStorage.setItem('nihonlink_disclaimer_shown', '1');
    banner.hidden = false;

    // Cek prefers-reduced-motion
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // Animasi masuk: fade + slide-down ringan
    if (!reduced) {
        banner.style.opacity = '0';
        banner.style.transform = 'translateY(-100%)';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                banner.style.transition = 'opacity 0.35s ease, transform 0.35s ease';
                banner.style.opacity    = '1';
                banner.style.transform  = 'translateY(0)';
            });
        });
    }

    // Tombol tutup
    function dismissBanner() {
        if (reduced) {
            banner.remove();
            return;
        }
        banner.style.opacity   = '0';
        banner.style.transform = 'translateY(-100%)';
        banner.addEventListener('transitionend', () => banner.remove(), { once: true });
    }

    closeBtn?.addEventListener('click', dismissBanner);

    // Auto-tutup setelah 8 detik agar tidak mengganggu
    setTimeout(dismissBanner, 8000);
}
