/**
 * guest-gate.js – Modal ajakan login untuk fitur yang membutuhkan autentikasi
 *
 * Penggunaan:
 *   import { showGuestGate } from './guest-gate.js';
 *   showGuestGate('like');   // saat guest coba like
 *   showGuestGate('review'); // saat guest coba review
 *
 * Modal berisi pesan ramah + tombol Masuk dan Daftar.
 * Menutup saat klik backdrop, tekan Escape, atau klik ×.
 */

// Buat elemen modal sekali saja
let modalEl = null;

function buildModal() {
    if (modalEl) return;

    modalEl = document.createElement('div');
    modalEl.id        = 'guest-gate-modal';
    modalEl.className = 'guest-gate';
    modalEl.setAttribute('role', 'dialog');
    modalEl.setAttribute('aria-modal', 'true');
    modalEl.setAttribute('aria-labelledby', 'guest-gate-title');
    modalEl.setAttribute('aria-hidden', 'true');

    modalEl.innerHTML = `
        <div class="guest-gate__backdrop" id="guest-gate-backdrop"></div>
        <div class="guest-gate__box">
            <button class="guest-gate__close" id="guest-gate-close" aria-label="Tutup">
                <i class="fas fa-times" aria-hidden="true"></i>
            </button>

            <div class="guest-gate__icon" aria-hidden="true">
                <span id="guest-gate-emoji">❤️</span>
            </div>

            <h2 class="guest-gate__title" id="guest-gate-title">
                Masuk untuk Melanjutkan
            </h2>

            <p class="guest-gate__desc" id="guest-gate-desc">
                Kamu perlu masuk akun terlebih dahulu untuk memberi like pada lokasi ini.
            </p>

            <div class="guest-gate__actions">
                <a href="${window.nihonlinkUrls?.login || '/login'}" class="guest-gate__btn guest-gate__btn--primary">
                    <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
                    Masuk
                </a>
                <a href="${window.nihonlinkUrls?.register || '/register'}" class="guest-gate__btn guest-gate__btn--outline">
                    <i class="fas fa-user-plus" aria-hidden="true"></i>
                    Daftar Sekarang
                </a>
            </div>

            <p class="guest-gate__hint">
                Gratis! Bergabung dan tandai lokasi favoritmu.
            </p>
        </div>
    `;

    document.body.appendChild(modalEl);

    // Event: tutup saat klik backdrop
    modalEl.querySelector('#guest-gate-backdrop')?.addEventListener('click', closeGuestGate);

    // Event: tutup saat klik ×
    modalEl.querySelector('#guest-gate-close')?.addEventListener('click', closeGuestGate);

    // Event: tutup saat tekan Escape
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && modalEl?.getAttribute('aria-hidden') === 'false') {
            closeGuestGate();
        }
    });
}

/**
 * Tampilkan modal guest gate
 * @param {'like'|'review'} feature – Fitur yang dicoba diakses guest
 */
export function showGuestGate(feature = 'like') {
    buildModal();

    const emoji  = modalEl.querySelector('#guest-gate-emoji');
    const desc   = modalEl.querySelector('#guest-gate-desc');

    // Sesuaikan pesan berdasarkan fitur
    if (feature === 'review') {
        emoji.textContent = '✍️';
        desc.textContent  = 'Kamu perlu masuk akun terlebih dahulu untuk menulis ulasan pada lokasi ini.';
    } else {
        emoji.textContent = '❤️';
        desc.textContent  = 'Kamu perlu masuk akun terlebih dahulu untuk memberi like pada lokasi ini.';
    }

    // Tampilkan modal
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    modalEl.setAttribute('aria-hidden', 'false');
    modalEl.classList.add('guest-gate--open');

    const box = modalEl.querySelector('.guest-gate__box');
    if (!reduced) {
        box.style.transform = 'translateY(24px) scale(0.95)';
        box.style.opacity   = '0';
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                box.style.transition = 'transform 0.3s cubic-bezier(0.34,1.3,0.64,1), opacity 0.25s ease';
                box.style.transform  = 'translateY(0) scale(1)';
                box.style.opacity    = '1';
            });
        });
    }

    // Fokus ke tombol pertama untuk aksesibilitas
    setTimeout(() => {
        modalEl.querySelector('.guest-gate__btn--primary')?.focus();
    }, 100);

    // Lock scroll body
    document.body.style.overflow = 'hidden';
}

/**
 * Tutup modal guest gate
 */
export function closeGuestGate() {
    if (!modalEl) return;

    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;
    const box     = modalEl.querySelector('.guest-gate__box');

    if (!reduced && box) {
        box.style.transform = 'translateY(12px) scale(0.97)';
        box.style.opacity   = '0';
    }

    setTimeout(() => {
        modalEl.setAttribute('aria-hidden', 'true');
        modalEl.classList.remove('guest-gate--open');
        if (box) { box.style.transform = ''; box.style.opacity = ''; }
        document.body.style.overflow = '';
    }, reduced ? 0 : 250);
}
