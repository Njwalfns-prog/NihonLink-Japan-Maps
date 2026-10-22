/**
 * like.js – Sistem LIKE untuk card lokasi dan popup marker
 *
 * Fitur:
 * - Toggle liked/unliked
 * - Animasi "pop" ikon hati (scale 1→1.4→1)
 * - Partikel kelopak sakura keluar dari tombol
 * - Angka like bergeser (lama naik, baru masuk dari bawah)
 * - Status tersimpan di localStorage
 * - Toast notifikasi saat like/unlike
 *
 * TODO: Sambungkan ke backend dengan fetch() ke endpoint POST /locations/{id}/like
 */

import { showToast } from './toast.js';

// Ambil status like dari localStorage
function getLikedSet() {
    try {
        const raw = localStorage.getItem('jmap_likes');
        return new Set(raw ? JSON.parse(raw) : []);
    } catch {
        return new Set();
    }
}

// Simpan status like ke localStorage
function saveLikedSet(set) {
    try {
        localStorage.setItem('jmap_likes', JSON.stringify([...set]));
    } catch { /* ignore */ }
}

// State global likes (id lokasi → jumlah like sementara)
const likedSet = getLikedSet();
const likeCounts = {}; // { id: jumlah }

/**
 * Inisialisasi satu tombol like
 * @param {HTMLElement} btn - Elemen tombol .like-btn atau .popup-like-btn
 * @param {number|string} locationId
 * @param {number} initialCount
 */
export function initLikeButton(btn, locationId, initialCount) {
    const id = String(locationId);
    const isLiked = likedSet.has(id);

    // Inisialisasi count
    if (likeCounts[id] === undefined) {
        likeCounts[id] = initialCount;
    }

    // Render awal
    renderLikeBtn(btn, id, likedSet.has(id), likeCounts[id]);

    btn.addEventListener('click', (e) => {
        e.stopPropagation(); // Cegah klik menyebar ke card
        toggleLike(btn, id);
    });
}

/**
 * Toggle status like
 */
function toggleLike(btn, id) {
    const wasLiked = likedSet.has(id);
    const prevCount = likeCounts[id] || 0;
    const newCount  = wasLiked ? Math.max(0, prevCount - 1) : prevCount + 1;

    // Update state
    if (wasLiked) {
        likedSet.delete(id);
    } else {
        likedSet.add(id);
    }
    likeCounts[id] = newCount;
    saveLikedSet(likedSet);

    // Animasi
    renderLikeBtn(btn, id, !wasLiked, newCount, prevCount);

    if (!wasLiked) {
        spawnParticles(btn);
        btn.classList.add('pop');
        btn.addEventListener('animationend', () => btn.classList.remove('pop'), { once: true });
        // Toast like
        showToast('Ditambahkan ke favorit!', 'like', 2500);
    } else {
        // Toast unlike
        showToast('Dihapus dari favorit', 'info', 2000);
    }

    // Sinkronisasi semua tombol dengan id yang sama (card + popup)
    document.querySelectorAll(`[data-like-id="${id}"]`).forEach(b => {
        if (b !== btn) renderLikeBtn(b, id, !wasLiked, newCount, prevCount);
    });

    // ── TODO: Sambungkan ke backend ────────────────────────────
    // Uncomment baris berikut setelah endpoint tersedia:
    //
    // const method = wasLiked ? 'DELETE' : 'POST';
    // fetch(`/locations/${id}/like`, {
    //     method,
    //     headers: {
    //         'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
    //         'Accept': 'application/json',
    //     },
    // })
    // .then(res => res.ok ? res.json() : Promise.reject(res))
    // .then(data => {
    //     // Update count dari server bila perlu
    //     // likeCounts[id] = data.jumlah_like;
    // })
    // .catch(err => console.error('Gagal update like:', err));
    // ─────────────────────────────────────────────────────────
}

/**
 * Render visual tombol like
 */
function renderLikeBtn(btn, id, liked, count, prevCount) {
    btn.dataset.likeId = id;
    btn.classList.toggle('liked', liked);

    // Cari atau buat elemen ikon dan angka
    let icon = btn.querySelector('.like-btn__icon, .popup-like-icon');
    let wrapper = btn.querySelector('.like-count-wrapper');

    if (!icon) {
        icon = document.createElement('span');
        icon.className = btn.classList.contains('popup-like-btn')
            ? 'popup-like-icon' : 'like-btn__icon';
        btn.prepend(icon);
    }

    // Ikon hati (kosong / isi)
    icon.innerHTML = liked
        ? '<i class="fas fa-heart"></i>'
        : '<i class="far fa-heart"></i>';
    icon.style.color = liked ? 'var(--color-red)' : '';

    // Angka dengan animasi geser jika berubah
    if (!wrapper) {
        wrapper = document.createElement('span');
        wrapper.className = 'like-count-wrapper';
        const span = document.createElement('span');
        span.className = 'like-count';
        span.textContent = count;
        wrapper.appendChild(span);
        btn.appendChild(wrapper);
    } else if (prevCount !== undefined && prevCount !== count) {
        animateLikeCount(wrapper, count);
    } else {
        const span = wrapper.querySelector('.like-count');
        if (span) span.textContent = count;
    }
}

/**
 * Animasi angka like: lama geser ke atas, baru masuk dari bawah
 */
function animateLikeCount(wrapper, newCount) {
    const old = wrapper.querySelector('.like-count');
    if (old) {
        old.classList.add('slide-up');
        old.addEventListener('transitionend', () => old.remove(), { once: true });
    }

    const fresh = document.createElement('span');
    fresh.className = 'like-count slide-in';
    fresh.textContent = newCount;
    wrapper.appendChild(fresh);
}

/**
 * Ledakan partikel kecil berbentuk lingkaran/oval di sekitar tombol like
 */
function spawnParticles(btn) {
    // Hormati prefers-reduced-motion
    if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

    const colors = ['#C1272D', '#e84c52', '#ff8fa3', '#ffb3c1', '#fff0f3'];
    const count  = 8;

    for (let i = 0; i < count; i++) {
        const particle = document.createElement('span');
        particle.className = 'like-particle';

        // Sudut acak dalam lingkaran penuh
        const angle  = (i / count) * Math.PI * 2 + (Math.random() - 0.5) * 0.6;
        const dist   = Math.random() * 28 + 18; // Jarak 18–46px
        const tx     = `translate(${Math.cos(angle) * dist}px, ${Math.sin(angle) * dist}px)`;

        particle.style.setProperty('--tx', tx);
        particle.style.background = colors[Math.floor(Math.random() * colors.length)];
        particle.style.width  = (Math.random() * 5 + 4) + 'px';
        particle.style.height = (Math.random() * 5 + 4) + 'px';
        particle.style.borderRadius = Math.random() > 0.5 ? '50%' : '50% 50% 50% 0';
        particle.style.animationDelay = (Math.random() * 80) + 'ms';

        btn.appendChild(particle);
        particle.addEventListener('animationend', () => particle.remove(), { once: true });
    }
}
