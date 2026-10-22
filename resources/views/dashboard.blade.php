<x-app-layout>
    {{-- Slot header → ditangkap layout → render sebagai page-header gelap bergaya J-Map --}}
    <x-slot name="header">
        <h2>Dashboard Admin</h2>
    </x-slot>

    {{-- Konten dashboard --}}
    <div class="py-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            {{-- Notifikasi sukses --}}
            @if(session('success'))
            <div class="dashboard-alert dashboard-alert--success" role="alert">
                <i class="fas fa-check-circle" aria-hidden="true"></i>
                {{ session('success') }}
            </div>
            @endif

            {{-- Kartu selamat datang --}}
            <div class="dashboard-welcome" data-aos="fade-up">
                <div class="dashboard-welcome__text">
                    <h3 class="dashboard-welcome__title">
                        Selamat Datang, <span>{{ Auth::user()->name }}</span>! 👋
                    </h3>
                    <p class="dashboard-welcome__desc">
                        Kelola data lokasi subkultur Jepang di Indonesia melalui panel ini.
                    </p>
                </div>
                <a href="{{ route('places.create') }}" class="btn btn--primary">
                    <i class="fas fa-plus" aria-hidden="true"></i> Tambah Lokasi Baru
                </a>
            </div>

            {{-- Stat cards --}}
            <div class="dashboard-stats" data-aos="fade-up" data-aos-delay="80">
                <div class="dashboard-stat-card">
                    <span class="dashboard-stat-card__icon">📍</span>
                    <div>
                        <p class="dashboard-stat-card__number">—</p>
                        <p class="dashboard-stat-card__label">Total Lokasi</p>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <span class="dashboard-stat-card__icon">🎌</span>
                    <div>
                        <p class="dashboard-stat-card__number">—</p>
                        <p class="dashboard-stat-card__label">Kategori</p>
                    </div>
                </div>
                <div class="dashboard-stat-card">
                    <span class="dashboard-stat-card__icon">👤</span>
                    <div>
                        <p class="dashboard-stat-card__number">{{ Auth::user()->created_at->diffForHumans() }}</p>
                        <p class="dashboard-stat-card__label">Bergabung</p>
                    </div>
                </div>
            </div>

            {{-- Quick action panel --}}
            <div class="dashboard-card" data-aos="fade-up" data-aos-delay="160">
                <div class="dashboard-card__header">
                    <h4 class="dashboard-card__title">Menu Cepat</h4>
                </div>
                <div class="dashboard-empty" style="padding: 2rem;">
                    <span class="dashboard-empty__icon">🗺️</span>
                    <p class="dashboard-empty__title">Kelola Lokasi Peta</p>
                    <p class="dashboard-empty__desc">Tambah, edit, atau hapus lokasi subkultur Jepang di Indonesia.</p>
                    <div style="display:flex; gap:12px; flex-wrap:wrap; justify-content:center; margin-top:8px;">
                        <a href="{{ route('places.index') }}" class="btn btn--outline-dark">
                            <i class="fas fa-list"></i> Lihat Semua Lokasi
                        </a>
                        <a href="{{ route('places.create') }}" class="btn btn--primary">
                            <i class="fas fa-plus"></i> Tambah Lokasi Baru
                        </a>
                    </div>
                </div>
            </div>

            {{-- Quick links --}}
            <div class="dashboard-links" data-aos="fade-up" data-aos-delay="240">
                <a href="{{ url('/') }}" class="dashboard-link-card">
                    <span class="dashboard-link-card__icon">🗾</span>
                    <span>Lihat Peta Publik</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="{{ route('places.create') }}" class="dashboard-link-card">
                    <span class="dashboard-link-card__icon">➕</span>
                    <span>Tambah Lokasi</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
                <a href="{{ route('profile.edit') }}" class="dashboard-link-card">
                    <span class="dashboard-link-card__icon">⚙️</span>
                    <span>Pengaturan Profil</span>
                    <i class="fas fa-arrow-right" aria-hidden="true"></i>
                </a>
            </div>

        </div>
    </div>
</x-app-layout>
