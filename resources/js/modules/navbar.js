/**
 * navbar.js – Perilaku navbar sticky NihonLink
 * - Transparan di atas peta penuh, solid setelah scroll
 * - Toggle menu mobile (hamburger)
 * - Toggle dropdown avatar user
 * - Smooth scroll untuk link ankor
 */

export function initNavbar() {
    const navbar     = document.getElementById('navbar');
    const toggle     = document.getElementById('navbar-toggle');
    const menu       = document.getElementById('navbar-mobile-menu');
    const avatarBtn  = document.getElementById('navbar-avatar-btn');
    const dropdown   = document.getElementById('navbar-dropdown');

    if (!navbar) return;

    // ── Scroll: ubah kelas navbar ──────────────────────────────
    let ticking = false;

    function onScroll() {
        if (!ticking) {
            requestAnimationFrame(() => {
                if (window.scrollY > 20) {
                    navbar.classList.add('scrolled');
                } else {
                    navbar.classList.remove('scrolled');
                }
                ticking = false;
            });
            ticking = true;
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    onScroll(); // Cek posisi awal

    // ── Menu mobile (hamburger) ─────────────────────────────────
    if (toggle && menu) {
        toggle.addEventListener('click', () => {
            const isOpen = toggle.getAttribute('aria-expanded') === 'true';
            toggle.setAttribute('aria-expanded', String(!isOpen));
            menu.setAttribute('aria-hidden', String(isOpen));
            menu.classList.toggle('open');
        });

        // Tutup menu mobile saat klik di luar navbar
        document.addEventListener('click', (e) => {
            if (!navbar.contains(e.target)) {
                toggle.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
                menu.classList.remove('open');
            }
        });
    }

    // ── Dropdown avatar ─────────────────────────────────────────
    if (avatarBtn && dropdown) {
        avatarBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            const isOpen = avatarBtn.getAttribute('aria-expanded') === 'true';
            avatarBtn.setAttribute('aria-expanded', String(!isOpen));
            dropdown.setAttribute('aria-hidden', String(isOpen));
            dropdown.classList.toggle('open');
        });

        // Tutup dropdown saat klik di luar
        document.addEventListener('click', (e) => {
            const userMenu = document.getElementById('navbar-user-menu');
            if (userMenu && !userMenu.contains(e.target)) {
                avatarBtn.setAttribute('aria-expanded', 'false');
                dropdown.setAttribute('aria-hidden', 'true');
                dropdown.classList.remove('open');
            }
        });

        // Tutup dropdown saat tekan Escape
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                avatarBtn.setAttribute('aria-expanded', 'false');
                dropdown.setAttribute('aria-hidden', 'true');
                dropdown.classList.remove('open');
                avatarBtn.focus();
            }
        });
    }

    // ── Smooth scroll untuk link ankor ─────────────────────────
    document.querySelectorAll('a[href^="#"], a[href*="#"]').forEach(link => {
        link.addEventListener('click', (e) => {
            const href = link.getAttribute('href');
            const hashIndex = href.indexOf('#');
            if (hashIndex === -1) return;

            const hash   = href.slice(hashIndex);
            const target = document.querySelector(hash);
            if (!target) return;

            e.preventDefault();
            const offset = parseInt(
                getComputedStyle(document.documentElement).getPropertyValue('--navbar-height') || '60',
                10
            );

            window.scrollTo({
                top: target.getBoundingClientRect().top + window.scrollY - offset,
                behavior: 'smooth',
            });

            // Tutup menu mobile jika terbuka
            if (menu) {
                toggle?.setAttribute('aria-expanded', 'false');
                menu.setAttribute('aria-hidden', 'true');
                menu.classList.remove('open');
            }

            // Tutup dropdown jika terbuka
            if (dropdown) {
                avatarBtn?.setAttribute('aria-expanded', 'false');
                dropdown.setAttribute('aria-hidden', 'true');
                dropdown.classList.remove('open');
            }
        });
    });
}
