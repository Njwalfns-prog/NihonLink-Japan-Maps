/**
 * toast.js – Sistem notifikasi toast ringan
 *
 * Penggunaan:
 *   import { showToast } from './toast.js';
 *   showToast('Berhasil disimpan!', 'success');
 *   showToast('Terjadi error', 'error');
 *   showToast('Info penting', 'info');
 */

let toastContainer = null;

// Buat container toast sekali saja
function getContainer() {
    if (toastContainer) return toastContainer;

    toastContainer = document.createElement('div');
    toastContainer.id = 'toast-container';
    toastContainer.setAttribute('aria-live', 'polite');
    toastContainer.setAttribute('aria-atomic', 'false');
    Object.assign(toastContainer.style, {
        position: 'fixed',
        bottom:   '24px',
        right:    '24px',
        zIndex:   '9998',
        display:  'flex',
        flexDirection: 'column',
        gap:      '10px',
        pointerEvents: 'none',
    });

    document.body.appendChild(toastContainer);
    return toastContainer;
}

/**
 * Tampilkan sebuah toast
 * @param {string} message – Pesan yang ditampilkan
 * @param {'success'|'error'|'info'|'like'} type – Tipe toast
 * @param {number} duration – Durasi milidetik (default 3000)
 */
export function showToast(message, type = 'info', duration = 3000) {
    // Hormati prefers-reduced-motion: langsung hilang tanpa animasi
    const reduced = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const container = getContainer();

    // Ikon per tipe
    const icons = {
        success: '✅',
        error:   '❌',
        info:    'ℹ️',
        like:    '❤️',
    };

    // Warna aksen per tipe
    const accents = {
        success: '#10b981',
        error:   '#ef4444',
        info:    '#3b82f6',
        like:    '#C1272D',
    };

    const toast = document.createElement('div');
    toast.className = `jmap-toast jmap-toast--${type}`;
    toast.setAttribute('role', 'status');
    toast.setAttribute('aria-label', message);
    toast.innerHTML = `
        <span class="jmap-toast__icon" aria-hidden="true">${icons[type] || icons.info}</span>
        <span class="jmap-toast__msg">${message}</span>
    `;

    Object.assign(toast.style, {
        display:       'flex',
        alignItems:    'center',
        gap:           '10px',
        padding:       '12px 20px',
        borderRadius:  '12px',
        background:    'rgba(26,26,26,0.96)',
        backdropFilter:'blur(12px)',
        color:         '#fff',
        fontSize:      '0.88rem',
        fontWeight:    '500',
        fontFamily:    'Inter, sans-serif',
        boxShadow:     '0 8px 30px rgba(0,0,0,.25)',
        borderLeft:    `3px solid ${accents[type] || accents.info}`,
        pointerEvents: 'auto',
        cursor:        'default',
        minWidth:      '220px',
        maxWidth:      '320px',
        transform:     reduced ? 'none' : 'translateX(120%)',
        opacity:       reduced ? '1' : '0',
        transition:    reduced ? 'none' : 'transform 300ms cubic-bezier(0.4,0,0.2,1), opacity 300ms',
    });

    container.appendChild(toast);

    // Animasi masuk
    if (!reduced) {
        requestAnimationFrame(() => {
            requestAnimationFrame(() => {
                toast.style.transform = 'translateX(0)';
                toast.style.opacity   = '1';
            });
        });
    }

    // Auto hilang
    const hide = () => {
        if (!reduced) {
            toast.style.transform = 'translateX(120%)';
            toast.style.opacity   = '0';
            toast.addEventListener('transitionend', () => toast.remove(), { once: true });
        } else {
            toast.remove();
        }
    };

    const timer = setTimeout(hide, duration);

    // Klik untuk menutup manual
    toast.addEventListener('click', () => {
        clearTimeout(timer);
        hide();
    });
}
