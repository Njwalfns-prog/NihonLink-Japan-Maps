/**
 * ui.js – Fitur UI umum NihonLink:
 * - Tombol kembali ke atas (back-to-top)
 * - Update aria-pressed pada chip filter saat diklik
 * - Bottom sheet drag untuk sidebar di mobile
 */

export function initUI() {
    initBackToTop();
    initChipAriaSync();
    syncFooterCategoryNames();
    initBottomSheetDrag();
}

// Samakan nama di footer dengan label chip kategori pada peta.
function syncFooterCategoryNames() {
    const chips = [...document.querySelectorAll('.chip[data-filter]')];
    document.querySelectorAll('[data-category-name]').forEach(link => {
        const category = chips.find(chip => chip.dataset.filter === link.dataset.categoryName);
        if (category?.dataset.label) link.textContent = category.dataset.label;
    });
}

// ── Back to top ────────────────────────────────────────────────
function initBackToTop() {
    const btn = document.getElementById('back-to-top');
    if (!btn) return;

    let ticking = false;

    window.addEventListener('scroll', () => {
        if (!ticking) {
            requestAnimationFrame(() => {
                if (window.scrollY > 300) {
                    btn.classList.add('visible');
                } else {
                    btn.classList.remove('visible');
                }
                ticking = false;
            });
            ticking = true;
        }
    }, { passive: true });

    btn.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' });
    });
}

// ── Chip filter aria-pressed sync ─────────────────────────────
function initChipAriaSync() {
    document.querySelectorAll('.chip[data-filter]').forEach(chip => {
        chip.addEventListener('click', () => {
            // Update aria-pressed untuk aksesibilitas
            document.querySelectorAll('.chip[data-filter]').forEach(c => {
                c.setAttribute('aria-pressed', 'false');
                c.classList.remove('active');
            });
            chip.setAttribute('aria-pressed', 'true');
            chip.classList.add('active');
        });
    });
}

// ── Bottom Sheet Drag (sidebar mobile) ────────────────────────
// Memungkinkan sidebar digeser naik/turun di atas peta saat di HP
function initBottomSheetDrag() {
    const sidebar = document.getElementById('map-sidebar');
    const handle  = document.getElementById('sidebar-handle');
    if (!sidebar || !handle) return;

    let startY    = 0;
    let startH    = 0;
    let isDragging = false;

    // Hanya aktif di layar ≤768px
    function isMobile() {
        return window.innerWidth <= 768;
    }

    handle.addEventListener('pointerdown', (e) => {
        if (!isMobile()) return;
        isDragging = true;
        startY = e.clientY;
        startH = sidebar.offsetHeight;
        handle.setPointerCapture(e.pointerId);
        sidebar.style.transition = 'none'; // Matikan transisi saat drag
    });

    handle.addEventListener('pointermove', (e) => {
        if (!isDragging) return;
        const dy   = startY - e.clientY; // Positif = geser ke atas
        const newH = Math.min(
            window.innerHeight * 0.8,       // Maks 80vh
            Math.max(window.innerHeight * 0.12, startH + dy) // Min 12vh
        );
        sidebar.style.height = newH + 'px';
    });

    handle.addEventListener('pointerup', () => {
        if (!isDragging) return;
        isDragging = false;
        sidebar.style.transition = ''; // Kembalikan transisi

        // Snap ke posisi terdekat (collapsed / default / expanded)
        const h    = sidebar.offsetHeight;
        const vh   = window.innerHeight;

        sidebar.classList.remove('sheet-expanded', 'sheet-collapsed');

        if (h < vh * 0.25) {
            sidebar.classList.add('sheet-collapsed');
        } else if (h > vh * 0.55) {
            sidebar.classList.add('sheet-expanded');
        }
        // Antara 25%–55% = posisi default (tidak ada kelas tambahan)
    });

    // Klik handle saat collapsed → langsung expanded
    handle.addEventListener('click', () => {
        if (!isMobile()) return;
        if (sidebar.classList.contains('sheet-collapsed')) {
            sidebar.classList.remove('sheet-collapsed');
        } else if (!sidebar.classList.contains('sheet-expanded')) {
            sidebar.classList.add('sheet-expanded');
        } else {
            sidebar.classList.remove('sheet-expanded');
        }
    });
}
