/**
 * map.js – Inisialisasi peta Leaflet dan semua fitur interaksi
 *
 * Fitur:
 * - Marker custom L.divIcon (pin merah + ikon kategori)
 * - Efek pulse saat hover marker
 * - Popup fade + slide-up dengan tombol like + tombol Google Maps
 * - Klik card → map.flyTo ke lokasi, marker berdenyut
 * - Filter chip: marker fade/tampil + card ter-filter
 * - Empty state saat tidak ada hasil
 * - Staggered fade-in card saat load
 * - Search bar real-time
 * - Tombol "Buka di Google Maps" di setiap card dan popup
 * - Guest gate: guest tidak bisa like/review (modal ajakan login)
 */

import L from 'leaflet';
import 'leaflet/dist/leaflet.css';
import { initLikeButton } from './like.js';
import { showGuestGate }  from './guest-gate.js';

// ── Data dummy lokasi (8 kota di Indonesia) ────────────────────────────────
// TODO: Ganti dengan data dari backend ($places) bila diperlukan via API
export const DUMMY_LOCATIONS = [
    {
        id: 1,
        nama: 'Harajuku Cosplay Space',
        kategori: 'cosplay',
        lat: -6.2088,
        lng: 106.8456,
        kota: 'Jakarta',
        deskripsi: 'Studio cosplay dan penyewaan kostum anime terlengkap di Jakarta. Tersedia studio foto, rias wajah profesional, dan koleksi 500+ kostum.',
        jumlah_like: 128,
        image: null,
    },
    {
        id: 2,
        nama: 'Anime Festival Asia Jakarta',
        kategori: 'event',
        lat: -6.1751,
        lng: 106.8272,
        kota: 'Jakarta',
        deskripsi: 'Festival anime tahunan terbesar di Asia Tenggara. Menampilkan penampilan band anime, guest dari Jepang, dan bazar merchandise.',
        jumlah_like: 374,
        image: null,
    },
    {
        id: 3,
        nama: 'Komunitas Otaku Bandung',
        kategori: 'komunitas',
        lat: -6.9175,
        lng: 107.6191,
        kota: 'Bandung',
        deskripsi: 'Komunitas pecinta anime & manga terbesar di Bandung. Rutin mengadakan nonton bareng, diskusi, dan gathering bulanan.',
        jumlah_like: 89,
        image: null,
    },
    {
        id: 4,
        nama: 'Akihabara Store Surabaya',
        kategori: 'merchandise',
        lat: -7.2575,
        lng: 112.7521,
        kota: 'Surabaya',
        deskripsi: 'Toko merchandise anime, manga, dan figur koleksi. Import langsung dari Jepang, stok selalu update tiap minggu.',
        jumlah_like: 203,
        image: null,
    },
    {
        id: 5,
        nama: 'Sakura Matsuri Yogyakarta',
        kategori: 'event',
        lat: -7.7956,
        lng: 110.3695,
        kota: 'Yogyakarta',
        deskripsi: 'Festival musim semi bertema sakura. Ada pertunjukan tradisional Jepang, workshop origami, dan pameran fotografi.',
        jumlah_like: 156,
        image: null,
    },
    {
        id: 6,
        nama: 'Manga Library Medan',
        kategori: 'komunitas',
        lat: 3.5952,
        lng: 98.6722,
        kota: 'Medan',
        deskripsi: 'Perpustakaan manga terbesar di Sumatera. Koleksi 10.000+ volume, tersedia ruang baca nyaman dan spot foto bertema Jepang.',
        jumlah_like: 67,
        image: null,
    },
    {
        id: 7,
        nama: 'Cosplay Championship Bali',
        kategori: 'cosplay',
        lat: -8.4095,
        lng: 115.1889,
        kota: 'Bali',
        deskripsi: 'Kompetisi cosplay internasional tahunan. Peserta dari seluruh Asia, berhadiah perjalanan ke Jepang untuk pemenang.',
        jumlah_like: 445,
        image: null,
    },
    {
        id: 8,
        nama: 'Japan Culture Center Makassar',
        kategori: 'komunitas',
        lat: -5.1477,
        lng: 119.4327,
        kota: 'Makassar',
        deskripsi: 'Pusat kebudayaan Jepang di Sulawesi. Kelas bahasa Jepang, kegiatan budaya, dan perpustakaan koleksi buku Jepang.',
        jumlah_like: 91,
        image: null,
    },
];

// Warna dan ikon per kategori
const CATEGORY_CONFIG = {
    cosplay:     { color: '#8b5cf6', icon: '🎭', label: 'Cosplay' },
    event:       { color: '#f59e0b', icon: '🎉', label: 'Event' },
    komunitas:   { color: '#10b981', icon: '👥', label: 'Komunitas' },
    merchandise: { color: '#3b82f6', icon: '🛍️', label: 'Merchandise' },
    anime:       { color: '#ec4899', icon: '🎌', label: 'Anime' },
    default:     { color: '#C1272D', icon: '📍', label: 'Lokasi' },
};

function normalizeCategory(kategori) {
    return String(kategori || '').trim().toLowerCase().replace(/\s+/g, '_');
}

export function getCategoryConfig(kategori) {
    return CATEGORY_CONFIG[normalizeCategory(kategori)] || CATEGORY_CONFIG.default;
}

// Cek apakah pengguna sudah login (dikirim dari Blade via window.nihonlinkAuth)
function isAuthenticated() {
    return window.nihonlinkAuth?.authenticated === true;
}

// State filter aktif
let activeFilter   = 'semua';
let searchQuery    = '';
let mapInstance    = null;
let markerMap      = {};     // id → { marker, loc }
let activeMarkerId = null;

/**
 * Buat ikon marker kustom
 */
function createMarkerIcon(kategori, active = false) {
    const cfg  = getCategoryConfig(kategori);
    const size = active ? 44 : 38;

    return L.divIcon({
        className: 'jmap-marker',
        html: `
            <div class="jmap-marker-pin" style="
                width: ${size}px;
                height: ${size}px;
                background: ${cfg.color};
                border: 3px solid white;
                border-radius: 50% 50% 50% 0;
                transform: rotate(-45deg);
                box-shadow: 0 3px 12px rgba(0,0,0,.35);
                transition: all 250ms ease;
                position: relative;
            ">
                <div style="
                    position: absolute;
                    inset: 0;
                    display: flex;
                    align-items: center;
                    justify-content: center;
                    transform: rotate(45deg);
                    font-size: ${size * 0.4}px;
                    line-height: 1;
                ">${cfg.icon}</div>
            </div>
        `,
        iconSize:    [size, size],
        iconAnchor:  [size / 2, size],
        popupAnchor: [0, -(size + 4)],
    });
}

/**
 * Buat URL Google Maps Search dari koordinat
 * Menggunakan endpoint search (bukan directions) sesuai spesifikasi
 */
function gmapsUrl(lat, lng) {
    return `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`;
}

/**
 * Buat konten popup marker
 */
function createPopupContent(loc) {
    const cfg    = getCategoryConfig(loc.kategori);
    const imgUrl = loc.image
        ? `/storage/${loc.image}`
        : `https://images.unsplash.com/photo-1492571350019-22de08371fd3?q=80&w=400&auto=format&fit=crop`;
    const mapsUrl = gmapsUrl(loc.lat, loc.lng);

    return `
        <div class="custom-popup">
            <img
                class="custom-popup__img"
                src="${imgUrl}"
                alt="${loc.nama}"
                onerror="this.src='https://images.unsplash.com/photo-1492571350019-22de08371fd3?q=80&w=400&auto=format&fit=crop'"
                loading="lazy"
            >
            <div class="custom-popup__body">
                <p class="custom-popup__category">${cfg.icon} ${cfg.label}</p>
                <h3 class="custom-popup__name">${loc.nama}</h3>
                <p class="custom-popup__desc">${loc.deskripsi}</p>
                <div class="custom-popup__actions">

                    <button
                        class="popup-like-btn"
                        data-like-id="${loc.id}"
                        data-guest="${!isAuthenticated()}"
                        aria-label="Suka ${loc.nama}"
                    >
                        <span class="popup-like-icon">
                            <i class="far fa-heart"></i>
                        </span>
                        <span class="like-count-wrapper">
                            <span class="like-count">${loc.jumlah_like}</span>
                        </span>
                    </button>

                    <a
                        href="${mapsUrl}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="popup-gmaps-btn"
                        aria-label="Buka ${loc.nama} di Google Maps"
                        title="Buka di Google Maps"
                    >
                        <i class="fab fa-google" aria-hidden="true"></i>
                        <span>Google Maps</span>
                    </a>

                </div>
            </div>
        </div>
    `;
}

/**
 * Main: inisialisasi peta dan semua fitur
 */
export function initMap(locations, { deferInitialBounds = false } = {}) {
    const mapEl = document.getElementById('map');
    if (!mapEl) return;

    // Gunakan data dari parameter atau dummy
    const locs = (locations && locations.length) ? locations : DUMMY_LOCATIONS;

    // ── Inisialisasi Leaflet ──────────────────────────────────
    mapInstance = L.map('map', {
        center:          [-2.5489, 118.0149],
        zoom:            5,
        scrollWheelZoom: true,
        zoomControl:     false, // Posisikan sendiri
    });

    // Tile layer OpenStreetMap
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom:     19,
        attribution: '© <a href="https://www.openstreetmap.org">OpenStreetMap</a>',
    }).addTo(mapInstance);

    // Tombol zoom ke kanan bawah
    L.control.zoom({ position: 'bottomright' }).addTo(mapInstance);

    // ── Tambahkan marker ──────────────────────────────────────
    locs.forEach(loc => {
        const marker = L.marker([loc.lat, loc.lng], {
            icon: createMarkerIcon(loc.kategori),
            riseOnHover: true,
        });

        // Popup dengan animasi fade+slide
        marker.bindPopup(createPopupContent(loc), {
            maxWidth: 260,
            className: 'jmap-popup',
        });

        // Inisialisasi like button di popup saat terbuka
        marker.on('popupopen', () => {
            const btn = document.querySelector(`.popup-like-btn[data-like-id="${loc.id}"]`);
            if (btn) {
                if (isAuthenticated()) {
                    // User login: like berfungsi normal
                    initLikeButton(btn, loc.id, loc.jumlah_like);
                } else {
                    // Guest: klik like → tampilkan guest gate
                    btn.addEventListener('click', (e) => {
                        e.stopPropagation();
                        showGuestGate('like');
                    }, { once: true });
                }
            }

            setActivePulse(loc.id, true);
            activeMarkerId = loc.id;
        });

        marker.on('popupclose', () => {
            setActivePulse(loc.id, false);
            activeMarkerId = null;
        });

        // Efek hover pulse
        marker.on('mouseover', () => setActivePulse(loc.id, true));
        marker.on('mouseout', () => {
            if (activeMarkerId !== loc.id) setActivePulse(loc.id, false);
        });

        marker.addTo(mapInstance);
        markerMap[loc.id] = { marker, loc };
    });

    // Atur pandangan awal agar semua marker terlihat sekaligus.
    const allMarkers = Object.values(markerMap).map(item => item.marker);
    if (allMarkers.length) {
        requestAnimationFrame(() => {
            if (!mapInstance) return;

            // Ukur ulang setelah splash ditutup; intro mengatur bounds setelah animasi.
            mapInstance.invalidateSize({ pan: false });
            if (!deferInitialBounds) {
                mapInstance.fitBounds(L.featureGroup(allMarkers).getBounds(), {
                    padding: [36, 36],
                    maxZoom: 7,
                });
            }
        });
    }

    // ── Render card di panel sidebar ─────────────────────────
    renderCards(locs);

    // ── Filter chip ───────────────────────────────────────────
    initFilterChips(locs);

    // ── Search bar ────────────────────────────────────────────
    initSearch(locs);

    return mapInstance;
}

// Berikan konteks peta dan marker untuk animasi intro tanpa mengubah fiturnya.
export function getMapIntroContext() {
    const markers = Object.values(markerMap).map(item => item.marker);
    return {
        map: mapInstance,
        markers,
        bounds: markers.length ? L.featureGroup(markers).getBounds() : null,
    };
}

/**
 * Efek pulse pada marker (elemen div animasi)
 */
function setActivePulse(id, on) {
    const item = markerMap[id];
    if (!item) return;

    const markerEl = item.marker.getElement();
    if (!markerEl) return;

    if (on && !markerEl.querySelector('.marker-pulse')) {
        const pulse = document.createElement('div');
        pulse.className = 'marker-pulse';
        pulse.style.cssText = `
            position: absolute;
            top: -6px; left: -6px;
            right: -6px; bottom: -6px;
            border-radius: 50%;
            background: rgba(193,39,45,.3);
            animation: markerPulseAnim 1.2s ease-out infinite;
            pointer-events: none;
        `;
        markerEl.style.overflow = 'visible';
        markerEl.appendChild(pulse);
    } else if (!on) {
        markerEl.querySelector('.marker-pulse')?.remove();
    }
}

/**
 * Klik card lokasi → flyTo marker + buka popup + aktifkan card
 */
export function flyToLocation(id) {
    const item = markerMap[id];
    if (!item || !mapInstance) return;

    mapInstance.flyTo([item.loc.lat, item.loc.lng], 13, {
        animate: true,
        duration: 1.2,
        easeLinearity: 0.25,
    });

    setTimeout(() => item.marker.openPopup(), 1200);

    // Aktifkan card
    document.querySelectorAll('.location-card').forEach(c => c.classList.remove('active-card'));
    document.querySelector(`.location-card[data-id="${id}"]`)?.classList.add('active-card');

    // Di mobile: expand bottom sheet saat card diklik
    const sidebar = document.getElementById('map-sidebar');
    if (sidebar?.classList.contains('sheet-collapsed')) {
        sidebar.classList.remove('sheet-collapsed');
    }
}

/** Sesuaikan pandangan ke marker kategori yang dipilih. */
export function fitBoundsToCategory(category) {
    if (!mapInstance) return;

    const key = normalizeCategory(category);
    const matches = Object.values(markerMap)
        .filter(({ loc }) => normalizeCategory(loc.kategori) === key)
        .map(({ marker }) => marker);

    if (!matches.length) return;
    mapInstance.fitBounds(L.featureGroup(matches).getBounds(), {
        padding: [48, 48],
        maxZoom: 12,
    });
}

/**
 * Render daftar card di sidebar panel
 */
function renderCards(locs) {
    const listEl = document.getElementById('location-list');
    if (!listEl) return;

    listEl.innerHTML = '';

    const visible = filterLocs(locs);

    if (!visible.length) {
        listEl.innerHTML = `
            <div class="empty-state">
                <span class="empty-state__icon">🔍</span>
                <p class="empty-state__title">Tidak ada lokasi</p>
                <p class="empty-state__desc">Coba ubah filter atau kata kunci pencarian</p>
            </div>
        `;
        return;
    }

    visible.forEach((loc, i) => {
        const cfg    = getCategoryConfig(loc.kategori);
        const imgUrl = loc.image
            ? `/storage/${loc.image}`
            : `https://images.unsplash.com/photo-1492571350019-22de08371fd3?q=80&w=200&auto=format&fit=crop`;
        const mapsUrl = gmapsUrl(loc.lat, loc.lng);

        const card = document.createElement('div');
        card.className      = 'location-card';
        card.dataset.id     = loc.id;
        card.dataset.category = loc.kategori;
        card.setAttribute('role', 'button');
        card.setAttribute('tabindex', '0');
        card.setAttribute('aria-label', `Lihat ${loc.nama} di peta`);

        card.innerHTML = `
            <img
                class="location-card__thumb"
                src="${imgUrl}"
                alt="${loc.nama}"
                loading="lazy"
                onerror="this.src='https://images.unsplash.com/photo-1492571350019-22de08371fd3?q=80&w=200&auto=format&fit=crop'"
            >
            <div class="location-card__info">
                <span class="location-card__category">${cfg.icon} ${cfg.label}</span>
                <p class="location-card__name">${loc.nama}</p>
                <p class="location-card__desc">${loc.deskripsi}</p>
                ${loc.review_url ? `<div class="location-card__rating" aria-label="Rating ${Number(loc.rating || 0).toFixed(1)} dari 5">★ ${Number(loc.rating || 0).toFixed(1)} <span>(${loc.review_count || 0} ulasan)</span></div>` : ''}

                <div class="location-card__footer">
                    <button
                        class="like-btn"
                        data-like-id="${loc.id}"
                        data-guest="${!isAuthenticated()}"
                        aria-label="Suka ${loc.nama}"
                    ></button>

                    <a
                        href="${mapsUrl}"
                        target="_blank"
                        rel="noopener noreferrer"
                        class="card-gmaps-btn"
                        aria-label="Buka ${loc.nama} di Google Maps"
                        title="Buka di Google Maps"
                        onclick="event.stopPropagation()"
                    >
                        <i class="fab fa-google" aria-hidden="true"></i>
                        <span>Maps</span>
                    </a>
                    ${loc.review_url ? `<a href="${loc.review_url}" class="card-review-btn" onclick="event.stopPropagation()">Tulis ulasan</a>` : ''}
                </div>
            </div>
        `;

        // Klik card → flyTo (kecuali klik tombol-tombol dalam card)
        card.addEventListener('click', (e) => {
            if (e.target.closest('.like-btn, .card-gmaps-btn')) return;
            flyToLocation(loc.id);
        });
        card.addEventListener('keydown', e => {
            if (e.key === 'Enter' || e.key === ' ') flyToLocation(loc.id);
        });

        listEl.appendChild(card);

        // Staggered fade-in
        setTimeout(() => card.classList.add('visible'), i * 60 + 50);

        // Inisialisasi tombol like
        const likeBtn = card.querySelector('.like-btn');
        if (likeBtn) {
            if (isAuthenticated()) {
                // User login: like berfungsi normal
                initLikeButton(likeBtn, loc.id, loc.jumlah_like);
            } else {
                // Guest: render visual saja, klik → guest gate
                renderGuestLikeBtn(likeBtn, loc.jumlah_like);
                likeBtn.addEventListener('click', (e) => {
                    e.stopPropagation();
                    showGuestGate('like');
                });
            }
        }
    });
}

/**
 * Render tombol like untuk guest (visual saja, tidak berfungsi)
 */
function renderGuestLikeBtn(btn, count) {
    btn.innerHTML = `
        <span class="like-btn__icon"><i class="far fa-heart"></i></span>
        <span class="like-count-wrapper">
            <span class="like-count">${count}</span>
        </span>
    `;
    // Tambahkan kelas tanda "terkunci" untuk UX
    btn.classList.add('like-btn--guest');
}

/**
 * Filter lokasi berdasarkan activeFilter dan searchQuery
 */
function filterLocs(locs) {
    return locs.filter(loc => {
        const matchCat    = activeFilter === 'semua' || normalizeCategory(loc.kategori) === normalizeCategory(activeFilter);
        const matchSearch = !searchQuery ||
            loc.nama.toLowerCase().includes(searchQuery) ||
            loc.kota.toLowerCase().includes(searchQuery) ||
            loc.deskripsi.toLowerCase().includes(searchQuery);
        return matchCat && matchSearch;
    });
}

/**
 * Terapkan filter pada marker peta dan card panel
 */
function applyFilter(locs) {
    const visible = filterLocs(locs);
    const visIds  = new Set(visible.map(l => l.id));

    // Marker: tampil/sembunyi dengan opacity
    Object.entries(markerMap).forEach(([id, { marker }]) => {
        const el = marker.getElement();
        if (!el) return;

        if (visIds.has(Number(id))) {
            el.style.opacity       = '1';
            el.style.pointerEvents = 'auto';
            el.style.transition    = 'opacity 300ms ease';
        } else {
            el.style.opacity       = '0.15';
            el.style.pointerEvents = 'none';
        }
    });

    // Re-render card
    renderCards(locs);
}

/**
 * Inisialisasi chip filter
 */
function initFilterChips(locs) {
    document.querySelectorAll('.chip[data-filter]').forEach(chip => {
        chip.addEventListener('click', () => {
            document.querySelectorAll('.chip[data-filter]').forEach(c => c.classList.remove('active'));
            chip.classList.add('active');
            activeFilter = chip.dataset.filter;
            applyFilter(locs);
        });
    });
}

/**
 * Inisialisasi search bar
 */
function initSearch(locs) {
    const input = document.getElementById('location-search');
    if (!input) return;

    let debounceTimer;

    input.addEventListener('input', () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(() => {
            searchQuery = input.value.trim().toLowerCase();
            applyFilter(locs);
        }, 250);
    });
}
