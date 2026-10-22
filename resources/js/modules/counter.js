/**
 * counter.js – Counter statistik yang menghitung naik saat terlihat di layar
 * Menggunakan IntersectionObserver agar hanya trigger sekali
 */

export function initCounters() {
    // Hormati prefers-reduced-motion
    const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    const counters = document.querySelectorAll('[data-count]');
    if (!counters.length) return;

    const observer = new IntersectionObserver((entries) => {
        entries.forEach(entry => {
            if (!entry.isIntersecting) return;

            const el     = entry.target;
            const target = parseInt(el.dataset.count, 10);

            if (reducedMotion) {
                // Langsung tampilkan angka akhir
                el.textContent = target + (el.dataset.suffix || '');
                observer.unobserve(el);
                return;
            }

            animateCounter(el, target);
            observer.unobserve(el); // Hanya sekali
        });
    }, { threshold: 0.5 });

    counters.forEach(el => observer.observe(el));
}

/**
 * Animasi hitung naik dari 0 ke target
 * @param {HTMLElement} el
 * @param {number} target
 */
function animateCounter(el, target) {
    const duration = 1500; // ms
    const suffix   = el.dataset.suffix || '';
    const start    = performance.now();

    function update(now) {
        const elapsed  = now - start;
        const progress = Math.min(elapsed / duration, 1);

        // Easing easeOutCubic
        const eased = 1 - Math.pow(1 - progress, 3);
        const value = Math.floor(eased * target);

        el.textContent = value + suffix;

        if (progress < 1) {
            requestAnimationFrame(update);
        } else {
            el.textContent = target + suffix;
        }
    }

    requestAnimationFrame(update);
}
