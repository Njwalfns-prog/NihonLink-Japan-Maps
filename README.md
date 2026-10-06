# J-Map

**J-Map (Japanese Culture Map)** adalah aplikasi web berbasis peta interaktif yang digunakan untuk membantu menemukan dan mengenali berbagai lokasi yang berkaitan dengan subculture Jepang di Kota Tegal dan sekitarnya.

Aplikasi ini menyediakan informasi lokasi seperti tempat cosplay, komunitas jejepangan, toko, event, kuliner, dan berbagai tempat lain yang memiliki keterkaitan dengan budaya Jepang.

## 🎯 Tujuan

J-Map dibuat untuk memudahkan masyarakat, khususnya penggemar budaya Jepang, dalam menemukan berbagai lokasi jejepangan yang tersebar di wilayah Tegal.

## ✨ Fitur

- 🗺️ **Peta Interaktif** — Menampilkan lokasi jejepangan pada peta.
- 📍 **Informasi Lokasi** — Menampilkan nama, kategori, deskripsi, dan informasi lokasi.
- 🏷️ **Kategori Lokasi** — Mengelompokkan lokasi berdasarkan jenisnya.
- ➕ **Tambah Lokasi** — Pengguna dapat mengusulkan atau menambahkan lokasi baru.
- 🔐 **Autentikasi** — Sistem login dan registrasi pengguna.
- 👤 **Manajemen Profil** — Pengguna dapat mengelola informasi profil.
- 🛠️ **Admin** — Admin dapat mengelola data lokasi yang terdapat dalam sistem.

## 🗂️ Kategori

Beberapa kategori yang dapat digunakan antara lain:

- 🎭 Cosplay
- 🎌 Komunitas
- 🛍️ Toko
- 🍜 Kuliner
- 🎮 Game
- 🎤 Event
- 📚 Manga & Anime
- 🏯 Japanese Culture

## 🛠️ Teknologi

Project ini dibangun menggunakan:

- **Laravel**
- **PHP**
- **MySQL**
- **Blade**
- **Tailwind CSS**
- **JavaScript**
- **Vite**

## 🚀 Instalasi

Clone repository:

```bash
git clone https://github.com/apriyoda/j-map.git
cd j-map
```

Install dependency PHP:

```bash
composer install
```

Install dependency JavaScript:

```bash
npm install
```

Buat file environment:

```bash
cp .env.example .env
```

Generate application key:

```bash
php artisan key:generate
```

Atur konfigurasi database pada file `.env`, kemudian jalankan migration:

```bash
php artisan migrate
```

Jalankan server Laravel:

```bash
php artisan serve
```

Untuk menjalankan Vite:

```bash
npm run dev
```

## 📌 Status Project

**Development**

Project ini masih dalam tahap pengembangan dan fitur dapat terus bertambah sesuai kebutuhan.

## 👨‍💻 Developer

**Apriyoda Pratama**

Web Developer | Teknik Informatika

---

⭐ Jika project ini bermanfaat, jangan lupa memberikan **Star** pada repository.
=======
Perbaikan gambar broken telah diselesaikan. Touchpad navigation pada Leaflet map juga telah digunakan.
