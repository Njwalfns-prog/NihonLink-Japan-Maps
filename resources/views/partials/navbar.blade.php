{{--
    navbar.blade.php – Navbar ramping NihonLink
    - Logo NihonLink (gambar + teks)
    - Link navigasi: Peta, Kategori, Tentang
    - Guest: tombol Masuk / Daftar
    - User login: avatar inisial + dropdown menu
    - Admin: avatar + dropdown dengan tambahan Kelola (tidak ada Tambah Lokasi di sini untuk user biasa)
    - Mobile: hamburger + menu overlay
--}}
<header id="navbar" class="navbar" role="banner">
    <nav class="navbar__inner" aria-label="Navigasi utama">

        {{-- Logo NihonLink --}}
        <a href="{{ url('/') }}" class="navbar__logo" aria-label="Beranda NihonLink">
            <img
                src="{{ asset('images/nihonlink-logo.png.png') }}"
                alt="NihonLink"
                class="navbar__logo-img"
                width="36"
                height="36"
            >
            <span class="navbar__logo-text">NihonLink</span>
        </a>

        {{-- Link navigasi tengah --}}
        <ul class="navbar__links" role="list">
            <li><a href="{{ url('/') }}" class="navbar__link">Peta</a></li>
            <li><a href="{{ url('/') }}#kategori" class="navbar__link">Kategori</a></li>
            <li><a href="{{ url('/') }}#tentang" class="navbar__link">Tentang</a></li>
        </ul>

        {{-- Aksi kanan --}}
        <div class="navbar__actions">
            @guest
                {{-- Guest: tampilkan tombol Masuk & Daftar --}}
                <a href="{{ url('/login') }}" class="btn btn--outline btn--sm">Masuk</a>
                <a href="{{ url('/register') }}" class="btn btn--primary btn--sm">Daftar</a>
            @else
                {{-- User login: avatar dengan dropdown --}}
                <div class="navbar__user" id="navbar-user-menu">
                    <button
                        class="navbar__avatar"
                        id="navbar-avatar-btn"
                        aria-label="Menu akun {{ auth()->user()->name }}"
                        aria-expanded="false"
                        aria-haspopup="true"
                    >
                        {{-- Inisial nama pengguna --}}
                        <span class="navbar__avatar-initials" aria-hidden="true">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </span>
                        <i class="fas fa-chevron-down navbar__avatar-caret" aria-hidden="true"></i>
                    </button>

                    {{-- Dropdown menu --}}
                    <div class="navbar__dropdown" id="navbar-dropdown" role="menu" aria-hidden="true">
                        {{-- Info nama & email --}}
                        <div class="navbar__dropdown-header">
                            <p class="navbar__dropdown-name">{{ auth()->user()->name }}</p>
                            <p class="navbar__dropdown-email">{{ auth()->user()->email }}</p>
                        </div>
                        <div class="navbar__dropdown-divider"></div>

                        {{-- Menu untuk semua user login --}}
                        <a href="{{ url('/') }}" class="navbar__dropdown-item" role="menuitem">
                            <i class="fas fa-map" aria-hidden="true"></i> Peta
                        </a>
                        <a href="{{ url('/dashboard') }}" class="navbar__dropdown-item" role="menuitem">
                            <i class="fas fa-th-large" aria-hidden="true"></i> Dashboard
                        </a>
                        <a href="{{ route('profile.edit') }}" class="navbar__dropdown-item" role="menuitem">
                            <i class="fas fa-user" aria-hidden="true"></i> Profil
                        </a>

                        {{--
                            Menu Tambah Lokasi HANYA untuk admin.
                            TODO: Sesuaikan pengecekan role dengan sistem backend.
                            Referensi: navigation.blade.php baris 15 (auth()->user()->role === 'admin')
                        --}}
                        @if(auth()->user()->role === 'admin')
                        <div class="navbar__dropdown-divider"></div>
                        <a href="{{ url('/places/create') }}" class="navbar__dropdown-item navbar__dropdown-item--admin" role="menuitem">
                            <i class="fas fa-plus" aria-hidden="true"></i> Tambah Lokasi
                        </a>
                        <a href="{{ route('places.index') }}" class="navbar__dropdown-item navbar__dropdown-item--admin" role="menuitem">
                            <i class="fas fa-list" aria-hidden="true"></i> Kelola Lokasi
                        </a>
                        @endif

                        <div class="navbar__dropdown-divider"></div>

                        {{-- Logout --}}
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="navbar__dropdown-item navbar__dropdown-item--danger" role="menuitem">
                                <i class="fas fa-sign-out-alt" aria-hidden="true"></i> Keluar
                            </button>
                        </form>
                    </div>
                </div>
            @endguest
        </div>

        {{-- Hamburger untuk mobile --}}
        <button
            id="navbar-toggle"
            class="navbar__hamburger"
            aria-label="Buka menu"
            aria-expanded="false"
            aria-controls="navbar-mobile-menu"
        >
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
            <span class="hamburger-bar"></span>
        </button>
    </nav>

    {{-- Menu mobile --}}
    <div id="navbar-mobile-menu" class="navbar__mobile" aria-hidden="true" role="dialog" aria-label="Menu navigasi mobile">
        <ul role="list">
            <li><a href="{{ url('/') }}" class="navbar__mobile-link">🗾 Peta</a></li>
            <li><a href="{{ url('/') }}#kategori" class="navbar__mobile-link">🎌 Kategori</a></li>
            <li><a href="{{ url('/') }}#tentang" class="navbar__mobile-link">ℹ️ Tentang</a></li>

            @guest
                <li><a href="{{ url('/login') }}" class="navbar__mobile-link">🔐 Masuk</a></li>
                <li><a href="{{ url('/register') }}" class="navbar__mobile-link navbar__mobile-link--primary">✨ Daftar</a></li>
            @else
                <li><a href="{{ url('/dashboard') }}" class="navbar__mobile-link">📊 Dashboard</a></li>
                <li><a href="{{ route('profile.edit') }}" class="navbar__mobile-link">👤 Profil</a></li>
                {{--
                    Tambah Lokasi di menu mobile HANYA untuk admin.
                    TODO: Sesuaikan pengecekan role dengan sistem backend.
                --}}
                @if(auth()->user()->role === 'admin')
                <li><a href="{{ url('/places/create') }}" class="navbar__mobile-link navbar__mobile-link--admin">➕ Tambah Lokasi</a></li>
                <li><a href="{{ route('places.index') }}" class="navbar__mobile-link navbar__mobile-link--admin">📋 Kelola Lokasi</a></li>
                @endif
                <li>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="navbar__mobile-link navbar__mobile-link--danger">🚪 Keluar</button>
                    </form>
                </li>
            @endauth
        </ul>
    </div>
</header>
