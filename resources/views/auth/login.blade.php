<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'NihonLink J-Map') }}</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        html, body { height: 100%; margin: 0; }
        .hero-bg {
            background-image: url('https://images.unsplash.com/photo-1478860409698-8707f313ee8b?auto=format&fit=crop&w=1500&q=80');
            background-size: cover;
            background-position: center;
        }
        .kanji-strip {
            writing-mode: vertical-rl;
            text-orientation: mixed;
            background: rgba(153,27,27,0.85);
            color: rgba(255,255,255,0.85);
            font-size: 1rem;
            letter-spacing: 0.15em;
            padding: 1.5rem 0.5rem;
            user-select: none;
        }
        .tab-active {
            background: #e11d48;
            color: #fff;
        }
        .tab-inactive {
            background: #f1f5f9;
            color: #64748b;
        }
    </style>
</head>
<body class="font-sans antialiased h-full">
<div class="min-h-screen flex">

    {{-- ===== LEFT: HERO ===== --}}
    <div class="hidden lg:flex flex-1 relative hero-bg">
        {{-- Dark overlay --}}
        <div class="absolute inset-0 bg-black/50"></div>

        {{-- Hero content --}}
        <div class="relative z-10 flex flex-col justify-center items-start p-16 text-white max-w-xl">
            {{-- Logo --}}
            <div class="flex items-center gap-3 mb-8">
                <svg class="w-10 h-10 text-red-500" fill="currentColor" viewBox="0 0 24 24">
                    <path d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10 10-4.48 10-10S17.52 2 12 2zm0 18c-4.41 0-8-3.59-8-8s3.59-8 8-8 8 3.59 8 8-3.59 8-8 8zm-1-13h2v6h-2zm0 8h2v2h-2z"/>
                </svg>
                <span class="text-2xl font-bold tracking-wide">NihonLink J-MAP</span>
            </div>

            {{-- Headline --}}
            <h1 class="text-4xl font-bold leading-tight mb-4">
                Jelajahi Dunia<br>
                <span class="text-red-400">Subkultur Jepang</span><br>
                di Sekitarmu
            </h1>

            {{-- Description --}}
            <p class="text-base text-white/80 mb-8 leading-relaxed">
                Temukan lokasi, komunitas, dan tempat menarik seputar budaya Jepang.
                Dari restoran, toko anime, hingga event cosplay — semua ada di sini!
            </p>

            {{-- Badge Pills --}}
            <div class="flex flex-wrap gap-2">
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-red-600 text-white">🍜 Restoran Jepang</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-purple-600 text-white">📦 Toko Anime</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold bg-violet-500 text-white">🎭 Cosplay</span>
                <span class="px-3 py-1 rounded-full text-xs font-semibold border border-white/60 text-white">📅 Event</span>
            </div>
        </div>
    </div>

    {{-- ===== RIGHT: Kanji strip + Form ===== --}}
    <div class="flex">
        {{-- Kanji vertical strip --}}
        <div class="kanji-strip hidden lg:flex items-center">
            日本のサブカルチャー
        </div>

        {{-- Form area --}}
        <div class="flex items-center justify-center bg-slate-100 w-full lg:w-[480px] px-6 py-10">
            <div class="w-full max-w-md">

                {{-- Card --}}
                <div class="bg-white rounded-3xl shadow-2xl p-8">

                    {{-- Card header --}}
                    <div class="flex items-center gap-3 mb-1">
                        <svg class="w-7 h-7 text-red-500" viewBox="0 0 24 24" fill="currentColor">
                            <path d="M12 2a5 5 0 110 10A5 5 0 0112 2zm0 12c5.33 0 8 2.67 8 4v2H4v-2c0-1.33 2.67-4 8-4z"/>
                        </svg>
                        <h2 class="text-xl font-bold text-gray-800">Selamat Datang!</h2>
                    </div>
                    <p class="text-sm text-gray-500 mb-6 ml-10">Masuk atau daftar untuk melanjutkan ke NihonLink.</p>

                    {{-- Session status --}}
                    @if (session('status'))
                        <div class="mb-4 text-sm text-green-600 bg-green-50 rounded-lg px-4 py-2">
                            {{ session('status') }}
                        </div>
                    @endif

                    {{-- Tab Switcher --}}
                    <div class="flex rounded-full bg-slate-100 p-1 mb-6" id="tabSwitcher">
                        <button type="button" id="btnLogin"
                                class="flex-1 py-2 rounded-full text-sm font-semibold transition tab-active"
                                onclick="switchTab('login')">
                            Masuk
                        </button>
                        <button type="button" id="btnRegister"
                                class="flex-1 py-2 rounded-full text-sm font-semibold transition tab-inactive"
                                onclick="switchTab('register')">
                            Daftar
                        </button>
                    </div>

                    {{-- ===== LOGIN FORM ===== --}}
                    <div id="panelLogin">
                        <form method="POST" action="{{ route('login') }}" class="space-y-4">
                            @csrf

                            {{-- Email --}}
                            <div>
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-regular fa-envelope text-sm"></i>
                                    </span>
                                    <input id="email" name="email" type="email"
                                           value="{{ old('email') }}" required autofocus autocomplete="username"
                                           placeholder="email@contoh.com"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('email') border-rose-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-solid fa-lock text-sm"></i>
                                    </span>
                                    <input id="password" name="password" type="password"
                                           required autocomplete="current-password"
                                           placeholder="Kata sandi"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('password') border-rose-500 @enderror">
                                    <button type="button" onclick="togglePwd('password','eyeLogin')"
                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <i id="eyeLogin" class="fa-regular fa-eye text-sm"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Remember + Forgot --}}
                            <div class="flex items-center justify-between">
                                <label class="flex items-center gap-2 text-sm text-gray-600 cursor-pointer">
                                    <input type="checkbox" name="remember" class="rounded border-gray-300 text-rose-600 focus:ring-rose-500">
                                    Ingat saya
                                </label>
                                @if (Route::has('password.request'))
                                    <a href="{{ route('password.request') }}" class="text-sm text-red-600 hover:text-red-500">
                                        Lupa kata sandi?
                                    </a>
                                @endif
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white font-semibold py-2.5 rounded-xl transition">
                                Masuk
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </button>

                            {{-- Footer --}}
                            <p class="text-center text-sm text-gray-500 pt-1">
                                Belum punya akun?
                                <button type="button" onclick="switchTab('register')"
                                        class="text-red-600 font-medium hover:underline">
                                    Daftar sekarang
                                </button>
                            </p>
                        </form>
                    </div>

                    {{-- ===== REGISTER FORM ===== --}}
                    <div id="panelRegister" class="hidden">
                        <form method="POST" action="{{ route('register') }}" class="space-y-4">
                            @csrf

                            {{-- Name --}}
                            <div>
                                <label for="reg_name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-regular fa-user text-sm"></i>
                                    </span>
                                    <input id="reg_name" name="name" type="text"
                                           value="{{ old('name') }}" required autocomplete="name"
                                           placeholder="Nama lengkap"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('name') border-rose-500 @enderror">
                                </div>
                                @error('name')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Email --}}
                            <div>
                                <label for="reg_email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-regular fa-envelope text-sm"></i>
                                    </span>
                                    <input id="reg_email" name="email" type="email"
                                           value="{{ old('email') }}" required autocomplete="username"
                                           placeholder="email@contoh.com"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-4 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('email') border-rose-500 @enderror">
                                </div>
                                @error('email')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Password --}}
                            <div>
                                <label for="reg_password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-solid fa-lock text-sm"></i>
                                    </span>
                                    <input id="reg_password" name="password" type="password"
                                           required autocomplete="new-password"
                                           placeholder="Min. 8 karakter"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('password') border-rose-500 @enderror">
                                    <button type="button" onclick="togglePwd('reg_password','eyeReg')"
                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <i id="eyeReg" class="fa-regular fa-eye text-sm"></i>
                                    </button>
                                </div>
                                @error('password')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Confirm Password --}}
                            <div>
                                <label for="reg_password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
                                <div class="relative">
                                    <span class="absolute inset-y-0 left-3 flex items-center text-gray-400">
                                        <i class="fa-solid fa-lock text-sm"></i>
                                    </span>
                                    <input id="reg_password_confirmation" name="password_confirmation" type="password"
                                           required autocomplete="new-password"
                                           placeholder="Ulangi kata sandi"
                                           class="w-full rounded-xl border border-gray-200 bg-slate-50 pl-9 pr-10 py-2.5 text-sm focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 @error('password_confirmation') border-rose-500 @enderror">
                                    <button type="button" onclick="togglePwd('reg_password_confirmation','eyeRegConf')"
                                            class="absolute inset-y-0 right-3 flex items-center text-gray-400 hover:text-gray-600">
                                        <i id="eyeRegConf" class="fa-regular fa-eye text-sm"></i>
                                    </button>
                                </div>
                                @error('password_confirmation')
                                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            {{-- Submit --}}
                            <button type="submit"
                                    class="w-full flex items-center justify-center gap-2 bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white font-semibold py-2.5 rounded-xl transition">
                                Daftar
                                <i class="fa-solid fa-arrow-right text-sm"></i>
                            </button>

                            {{-- Footer --}}
                            <p class="text-center text-sm text-gray-500 pt-1">
                                Sudah punya akun?
                                <button type="button" onclick="switchTab('login')"
                                        class="text-red-600 font-medium hover:underline">
                                    Masuk di sini
                                </button>
                            </p>
                        </form>
                    </div>

                </div>{{-- end card --}}
            </div>
        </div>
    </div>

</div>

<script>
    function switchTab(tab) {
        const login = document.getElementById('panelLogin');
        const reg = document.getElementById('panelRegister');
        const btnL = document.getElementById('btnLogin');
        const btnR = document.getElementById('btnRegister');

        if (tab === 'login') {
            login.classList.remove('hidden');
            reg.classList.add('hidden');
            btnL.classList.replace('tab-inactive', 'tab-active');
            btnR.classList.replace('tab-active', 'tab-inactive');
        } else {
            reg.classList.remove('hidden');
            login.classList.add('hidden');
            btnR.classList.replace('tab-inactive', 'tab-active');
            btnL.classList.replace('tab-active', 'tab-inactive');
        }
    }

    function togglePwd(inputId, iconId) {
        const input = document.getElementById(inputId);
        const icon = document.getElementById(iconId);
        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.replace('fa-eye', 'fa-eye-slash');
        } else {
            input.type = 'password';
            icon.classList.replace('fa-eye-slash', 'fa-eye');
        }
    }

    // Auto-switch to register tab when ?tab=register or when register errors exist
    const urlParams = new URLSearchParams(window.location.search);
    const shouldShowRegister = urlParams.get('tab') === 'register'
        @if ($errors->hasAny(['name']) || $errors->any())
            || true
        @endif
        ;
    if (shouldShowRegister) {
        switchTab('register');
    }
</script>
</body>
</html>
