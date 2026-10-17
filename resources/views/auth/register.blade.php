<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar &mdash; NihonLink J-Map</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .jp-pattern {
            background-color: #0f172a;
            background-image:
                repeating-linear-gradient(
                    45deg,
                    rgba(225,29,72,0.04) 0px,
                    rgba(225,29,72,0.04) 1px,
                    transparent 1px,
                    transparent 12px
                ),
                repeating-linear-gradient(
                    -45deg,
                    rgba(225,29,72,0.04) 0px,
                    rgba(225,29,72,0.04) 1px,
                    transparent 1px,
                    transparent 12px
                );
        }
        .torii-accent {
            width: 3px;
            height: 48px;
            background: linear-gradient(to bottom, #fb7185, #e11d48);
            border-radius: 2px;
            display: inline-block;
        }
    </style>
</head>
<body class="bg-slate-900 text-white min-h-screen">

<div class="min-h-screen flex flex-col md:flex-row">

    {{-- LEFT PANEL --}}
    <div class="jp-pattern hidden md:flex md:w-1/2 flex-col items-center justify-center p-12 relative overflow-hidden">

        {{-- Decorative circle --}}
        <div class="absolute -top-24 -left-24 w-96 h-96 rounded-full bg-rose-600/10 blur-3xl pointer-events-none"></div>
        <div class="absolute bottom-0 right-0 w-64 h-64 rounded-full bg-rose-600/10 blur-2xl pointer-events-none"></div>

        {{-- Torii gate illustration (SVG) --}}
        <svg class="w-48 h-48 mb-8 opacity-90" viewBox="0 0 200 200" fill="none" xmlns="http://www.w3.org/2000/svg">
            {{-- Cross beam top --}}
            <rect x="20" y="40" width="160" height="12" rx="3" fill="#e11d48"/>
            <rect x="28" y="52" width="144" height="8" rx="2" fill="#fb7185"/>
            {{-- Columns --}}
            <rect x="44" y="60" width="14" height="120" rx="4" fill="#e11d48"/>
            <rect x="142" y="60" width="14" height="120" rx="4" fill="#e11d48"/>
            {{-- Base --}}
            <rect x="34" y="172" width="34" height="8" rx="4" fill="#fb7185"/>
            <rect x="132" y="172" width="34" height="8" rx="4" fill="#fb7185"/>
            {{-- Shimmer rings --}}
            <circle cx="100" cy="100" r="70" stroke="#fb7185" stroke-width="1" stroke-dasharray="4 6" opacity="0.3"/>
            <circle cx="100" cy="100" r="88" stroke="#e11d48" stroke-width="1" stroke-dasharray="2 8" opacity="0.15"/>
        </svg>

        {{-- Branding --}}
        <div class="text-center">
            <div class="flex items-center justify-center gap-3 mb-3">
                <span class="torii-accent"></span>
                <h1 class="text-4xl font-bold tracking-tight text-white">NihonLink</h1>
                <span class="torii-accent"></span>
            </div>
            <p class="text-rose-400 text-lg font-medium tracking-widest uppercase mb-6">J-Map</p>
            <p class="text-slate-400 text-base leading-relaxed max-w-sm">
                Komunitas peta interaktif destinasi &amp; kuliner Jepang.<br>
                Temukan, bagikan, dan eksplorasi bersama.
            </p>
        </div>

        {{-- Bottom decoration --}}
        <div class="absolute bottom-8 flex gap-2 opacity-30">
            @foreach([0,1,2,3,4] as $i)
                <div class="w-1.5 h-1.5 rounded-full bg-rose-400"></div>
            @endforeach
        </div>
    </div>

    {{-- RIGHT PANEL: Form --}}
    <div class="flex flex-1 items-center justify-center bg-slate-900 px-6 py-12 md:px-16">
        <div class="w-full max-w-md">

            {{-- Mobile branding --}}
            <div class="flex items-center gap-2 mb-8 md:hidden">
                <div class="w-8 h-8 rounded-full bg-rose-600 flex items-center justify-center">
                    <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 2a8 8 0 100 16A8 8 0 0010 2z"/>
                    </svg>
                </div>
                <span class="text-xl font-bold text-white">NihonLink J-Map</span>
            </div>

            <h2 class="text-3xl font-bold text-white mb-1">Buat Akun</h2>
            <p class="text-slate-400 mb-8 text-sm">Bergabunglah dan mulai jelajahi komunitas J-Map.</p>

            <form method="POST" action="{{ route('register') }}" class="space-y-5">
                @csrf

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-slate-300 mb-1">Nama Lengkap</label>
                    <input
                        id="name"
                        name="name"
                        type="text"
                        value="{{ old('name') }}"
                        required
                        autofocus
                        autocomplete="name"
                        class="w-full rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition
                               @error('name') border-rose-500 @enderror"
                        placeholder="Masukkan nama lengkap"
                    >
                    @error('name')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-slate-300 mb-1">Alamat Email</label>
                    <input
                        id="email"
                        name="email"
                        type="email"
                        value="{{ old('email') }}"
                        required
                        autocomplete="username"
                        class="w-full rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition
                               @error('email') border-rose-500 @enderror"
                        placeholder="email@contoh.com"
                    >
                    @error('email')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-slate-300 mb-1">Kata Sandi</label>
                    <input
                        id="password"
                        name="password"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition
                               @error('password') border-rose-500 @enderror"
                        placeholder="Min. 8 karakter"
                    >
                    @error('password')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-slate-300 mb-1">Konfirmasi Kata Sandi</label>
                    <input
                        id="password_confirmation"
                        name="password_confirmation"
                        type="password"
                        required
                        autocomplete="new-password"
                        class="w-full rounded-lg bg-slate-800 border border-slate-700 text-white placeholder-slate-500 px-4 py-2.5 text-sm
                               focus:outline-none focus:ring-2 focus:ring-rose-500 focus:border-rose-500 transition
                               @error('password_confirmation') border-rose-500 @enderror"
                        placeholder="Ulangi kata sandi"
                    >
                    @error('password_confirmation')
                        <p class="mt-1.5 text-xs text-rose-400">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Submit --}}
                <button
                    type="submit"
                    class="w-full bg-rose-600 hover:bg-rose-500 active:bg-rose-700 text-white font-semibold py-2.5 rounded-lg transition text-sm tracking-wide mt-2"
                >
                    Daftar Sekarang
                </button>

                {{-- Login link --}}
                <p class="text-center text-sm text-slate-400">
                    Sudah punya akun?
                    <a href="{{ route('login') }}" class="text-rose-400 hover:text-rose-300 font-medium transition">
                        Masuk di sini
                    </a>
                </p>
            </form>
        </div>
    </div>
</div>

</body>
</html>
