<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description" content="NihonLink – Peta interaktif subkultur Jepang di Indonesia. Temukan komunitas cosplay, event anime, dan lokasi unik.">

    <title>NihonLink - Peta Subkultur Jepang</title>
    <link rel="icon" type="image/png" href="{{ asset('images/nihonlink-logo.png.png') }}">

    {{-- Google Fonts: Noto Serif JP + Inter --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Noto+Serif+JP:wght@400;600;700&family=Inter:ital,wght@0,300;0,400;0,500;0,600;0,700;1,400&display=swap" rel="stylesheet">

    {{-- Font Awesome untuk ikon --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer">

    {{-- Vite: mengompilasi CSS (app.css + aos + leaflet) dan JS (app.js) --}}
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    {{-- Stack CSS tambahan per-halaman --}}
    @stack('styles')
</head>
<body class="antialiased">

    {{--
        Splash screen NihonLink.
        Halaman utama (welcome) meng-push partial splash ke stack ini.
        Halaman lain tidak push ke stack ini sehingga splash tidak tampil.
    --}}
    @stack('splash')

    {{-- Navbar sticky ramping --}}
    @include('partials.navbar')

    {{-- Banner disclaimer (tampil sekali per sesi via sessionStorage) --}}
    @include('partials.disclaimer')

    {{-- Konten halaman (slot Blade component) --}}
    <main id="main-content" tabindex="-1">

        {{--
            Jika halaman menyertakan slot $header (dashboard, places, dsb.),
            render page-header di bawah navbar dengan padding yang benar.
            Halaman welcome TIDAK mengirim $header (peta dimulai dari atas).
        --}}
        @isset($header)
        <div class="page-header">
            <div class="page-header__inner">
                {{ $header }}
            </div>
        </div>
        @endisset

        {{--
            Bungkus slot dengan page-body HANYA jika ada $header.
            Halaman welcome/peta mengelola padding-nya sendiri.
        --}}
        @isset($header)
        <div class="page-body">
            {{ $slot }}
        </div>
        @else
        {{ $slot }}
        @endisset

    </main>

    {{-- Footer --}}
    @include('partials.footer')

    {{--
        FAB Tambah Lokasi – HANYA untuk admin.
        TODO: Sesuaikan pengecekan role dengan sistem yang dipakai backend.
        Saat ini menggunakan auth()->user()->role === 'admin'
        (lihat navigation.blade.php untuk referensi pengecekan yang sama).
    --}}
    @auth
        @if(auth()->user()->role === 'admin')
        <a
            href="{{ url('/places/create') }}"
            class="fab-add"
            id="fab-add"
            aria-label="Tambah lokasi baru"
            data-tooltip="Tambah Lokasi"
        >
            <i class="fas fa-plus" aria-hidden="true"></i>
        </a>
        @endif
    @endauth

    {{-- Tombol kembali ke atas --}}
    <button
        id="back-to-top"
        class="back-to-top"
        aria-label="Kembali ke atas halaman"
        title="Kembali ke atas"
    >
        <i class="fas fa-chevron-up" aria-hidden="true"></i>
    </button>

    {{--
        Status autentikasi untuk JS (map.js, guest-gate.js, dll.)
        Dikirim sebelum @stack('scripts') agar tersedia saat modul dijalankan.
        JANGAN ekspos data sensitif di sini.
    --}}
    <script>
        window.nihonlinkAuth = {
            authenticated: {{ auth()->check() ? 'true' : 'false' }},
            {{-- TODO: tambahkan field lain jika dibutuhkan JS, misal userId --}}
        };
        window.nihonlinkUrls = {
            login:    '{{ url("/login") }}',
            register: '{{ url("/register") }}',
        };
    </script>

    {{-- Stack JS tambahan per-halaman (ditempatkan sebelum </body>) --}}
    @stack('scripts')

</body>
</html>
