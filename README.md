# 🏮 NihonLink – Peta Subkultur Jepang

**NihonLink** adalah website peta interaktif yang mengumpulkan informasi subkultur Jepang di Indonesia, seperti **cosplay, anime & manga, event, komunitas, dan merchandise**. Pengguna dapat menjelajahi lokasi di peta, melihat rekomendasi tempat, lalu membuka rute lewat Google Maps.

> ⚠️ **Disclaimer:** NihonLink hanyalah wadah informasi. Navigasi dan rute dibuka melalui Google Maps.

> 📌 **Catatan:** Repositori ini berisi bagian **frontend** (tampilan, animasi, dan interaksi). Kode backend (controller, model, dan database) dikelola di repositori tim backend, sehingga aplikasi penuh baru bisa dijalankan setelah keduanya digabung.

<!-- Ganti dengan screenshot atau GIF asli, simpan di folder docs/screenshots -->
<!-- ![Tampilan NihonLink](docs/screenshots/beranda.png) -->

---

## ✨ Fitur

### Untuk semua pengunjung (Guest)
- 🗺️ Peta interaktif (Leaflet.js) dengan marker berwarna per kategori
- 📍 Panel rekomendasi tempat di sisi kanan peta (menjadi *bottom sheet* di HP)
- 🔎 Pencarian dan filter kategori (Cosplay, Event, Komunitas, Merchandise, Anime)
- 🔗 Tombol **Buka di Google Maps** pada setiap lokasi
- 🌸 Animasi pembuka (splash logo) dengan tirai yang membuka ke peta
- 📱 Tampilan responsif (desktop, tablet, HP)

### Untuk pengguna yang login (User)
- ❤️ Memberi **like** pada lokasi (dengan animasi dan notifikasi *toast*)
- ⭐ Menulis ulasan dan rating *(dalam pengembangan)*

### Untuk Admin
- ➕ Menambah dan mengelola lokasi (dashboard admin)

> Satu halaman login dipakai bersama; hak akses dibedakan berdasarkan *role*.

---

## 🛠️ Teknologi

| Bagian | Teknologi |
|---|---|
| Backend | Laravel (PHP), MySQL |
| Frontend | Blade, CSS, JavaScript (modular), Vite |
| Peta | Leaflet.js + OpenStreetMap |
| Animasi | CSS animation, AOS |
| Navigasi eksternal | Google Maps (tautan) |

---

## 🚀 Menjalankan Proyek di Lokal

**Prasyarat:** PHP, Composer, Node.js (npm), dan MySQL (misalnya lewat XAMPP).

```bash
# 1. Clone repositori
git clone https://github.com/Njwalfns-prog/NihonLink-Japan-Maps.git
cd NihonLink-Japan-Maps

# 2. Pasang dependensi
composer install
npm install

# 3. Siapkan environment
cp .env.example .env
php artisan key:generate
# Atur koneksi database di file .env (DB_DATABASE, DB_USERNAME, DB_PASSWORD)

# 4. Migrasi database
php artisan migrate
# Jika tersedia seeder: php artisan migrate --seed

# 5. Jalankan (pakai dua terminal)
npm run dev          # terminal 1: Vite
php artisan serve    # terminal 2: server Laravel
```

Buka **http://127.0.0.1:8000** di browser.

Untuk build produksi: `npm run build`.

> 🖼️ Logo ada di `public/images/nihonlink-logo.png` (gunakan nama huruf kecil semua).

---

## 📁 Struktur Frontend (ringkas)

```
resources/
├── css/app.css              # gaya global, tema, animasi
├── js/
│   ├── app.js               # entry point
│   └── modules/             # satu modul per fitur (map, like, toast, splash, dst.)
└── views/
    ├── layouts/app.blade.php
    ├── partials/            # navbar, splash, disclaimer, stats, dst.
    └── welcome.blade.php    # halaman utama (peta)
```

---

## 🎨 Panduan Desain

- **Warna:** merah `#C1272D`, krem `#F7F1E3`, hitam `#1A1A1A`
- **Font:** Noto Serif JP (judul), Inter (isi)
- Animasi halus (200–500 ms) dan menghormati `prefers-reduced-motion`

---

## 🗺️ Rencana Pengembangan

- [x] Peta interaktif, filter kategori, dan marker
- [x] Like, toast, dan animasi UI
- [x] Splash screen logo dan halaman utama langsung ke peta
- [x] Disclaimer dan tombol Google Maps
- [ ] Ulasan dan rating (komentar + bintang)
- [ ] Restyle halaman login/register
- [ ] Intro peta (garis penghubung Jepang → Indonesia)
- [ ] Integrasi endpoint like dan ulasan ke backend

---

## 👥 Tim Pengembang

| Nama | Peran |
|---|---|
| Najwa Alief Nurshifa | Frontend Developer |
| Apri Yoda Pratama | Backend Developer |
| Eka Nurbella | UI/UX Designer |
| Wylson Marco Adelwin | Data & Research |

---

## 📄 Lisensi

Proyek ini dibuat untuk keperluan tugas perkuliahan. <!-- Tambahkan lisensi jika diperlukan -->

© 2026 NihonLink
