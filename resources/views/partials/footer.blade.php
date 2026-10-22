{{-- Footer dengan info tim dan tautan --}}
<footer id="tentang" class="footer" role="contentinfo">

    {{-- Pembatas motif seigaiha atas --}}
    <div class="seigaiha-divider seigaiha-divider--footer" aria-hidden="true"></div>

    <div class="footer__inner">
        {{-- Brand kolom --}}
        <div class="footer__brand">
            <a href="{{ url('/') }}" class="footer__logo" aria-label="Beranda NihonLink">
                <span class="footer__logo-icon" aria-hidden="true">⛩</span>
                <span class="footer__logo-text">NihonLink</span>
            </a>
            <p class="footer__brand-desc">
                Peta interaktif subkultur Jepang di Indonesia.
                Temukan komunitas, event, dan lokasi unik di sekitarmu.
            </p>
            <div class="footer__social" aria-label="Media sosial">
                <a href="#" class="footer__social-link" aria-label="Instagram">
                    <i class="fab fa-instagram" aria-hidden="true"></i>
                </a>
                <a href="#" class="footer__social-link" aria-label="Twitter / X">
                    <i class="fab fa-x-twitter" aria-hidden="true"></i>
                </a>
                <a href="#" class="footer__social-link" aria-label="GitHub">
                    <i class="fab fa-github" aria-hidden="true"></i>
                </a>
            </div>
        </div>

        {{-- Kolom navigasi --}}
        <nav class="footer__nav" aria-label="Tautan footer">
            <h3 class="footer__nav-title">Navigasi</h3>
            <ul role="list">
                <li><a href="{{ url('/') }}#peta" class="footer__nav-link">Jelajahi Peta</a></li>
                <li><a href="{{ url('/') }}#kategori" class="footer__nav-link">Kategori</a></li>
                @auth
                    <li><a href="{{ url('/dashboard') }}" class="footer__nav-link">Dashboard</a></li>
                    {{-- Tambah Lokasi hanya untuk admin. TODO: sesuaikan pengecekan role. --}}
                    @if(auth()->user()->role === 'admin')
                    <li><a href="{{ url('/places/create') }}" class="footer__nav-link">Tambah Lokasi</a></li>
                    @endif
                @else
                    <li><a href="{{ url('/login') }}" class="footer__nav-link">Masuk</a></li>
                    <li><a href="{{ url('/register') }}" class="footer__nav-link">Daftar</a></li>
                @endauth
            </ul>
        </nav>

        {{-- Kolom kategori --}}
        <nav class="footer__nav" aria-label="Kategori lokasi">
            <h3 class="footer__nav-title">Kategori</h3>
            <ul role="list">
                <li><a href="#" class="footer__nav-link" data-category-name="cosplay">Cosplay</a></li>
                <li><a href="#" class="footer__nav-link" data-category-name="anime">Anime</a></li>
                <li><a href="#" class="footer__nav-link" data-category-name="event">Event</a></li>
                <li><a href="#" class="footer__nav-link" data-category-name="komunitas">Komunitas</a></li>
                <li><a href="#" class="footer__nav-link" data-category-name="merchandise">Merchandise</a></li>
            </ul>
        </nav>

        {{-- Kolom tim --}}
        <div class="footer__team">
            <h3 class="footer__nav-title">Tim Pengembang</h3>
            <ul class="footer__team-list" role="list">
                <li class="footer__team-member">
                    <span class="footer__team-avatar" aria-hidden="true">🧑‍💻</span>
                    <span>Frontend Developer</span>
                </li>
                <li class="footer__team-member">
                    <span class="footer__team-avatar" aria-hidden="true">🧑‍💻</span>
                    <span>Backend Developer</span>
                </li>
                <li class="footer__team-member">
                    <span class="footer__team-avatar" aria-hidden="true">🎨</span>
                    <span>UI / UX Designer</span>
                </li>
                <li class="footer__team-member">
                    <span class="footer__team-avatar" aria-hidden="true">📊</span>
                    <span>Data & Research</span>
                </li>
            </ul>
        </div>
    </div>

    {{-- Bottom bar --}}
    <div class="footer__bottom">
        <p>
            &copy; {{ date('Y') }} <strong>NihonLink</strong> &ndash; Japanese Subculture Map Indonesia.
            Dibuat dengan <span class="footer__heart" aria-label="cinta">❤</span> untuk komunitas jejepangan.
        </p>
    </div>
</footer>
