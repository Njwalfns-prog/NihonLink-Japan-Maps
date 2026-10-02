<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>J-Map - Japanese Subculture Map</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        .leaflet-container { touch-action: none !important; outline: none; }
        .leaflet-popup-content { margin: 12px !important; }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50">
    <!-- Navbar -->
    <header class="bg-white shadow-2xl">
        <nav class="max-w-7xl mx-auto px-6 py-4 flex justify-between items-center">
            <!-- Kiri -->
            <a href="{{ url('/') }}" class="text-2xl font-bold text-gray-900">J-Map</a>

            <!-- Tengah -->
            <div class="flex gap-8">
                <a href="{{ url('/') }}" class="text-gray-600 hover:text-blue-600 transition font-medium">Beranda</a>
                <a href="#" class="text-gray-600 hover:text-blue-600 transition font-medium">Kategori</a>
                <a href="{{ url('/places/create') }}" class="text-gray-600 hover:text-blue-600 transition font-medium">Tambah Lokasi</a>
            </div>

            <!-- Kanan -->
            @auth
                <a href="{{ url('/dashboard') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">Dashboard</a>
            @else
                <a href="{{ url('/login') }}" class="text-gray-700 hover:text-blue-600 transition font-semibold">Login</a>
            @endauth
        </nav>
    </header>

    <!-- Container -->
    <div class="max-w-7xl mx-auto p-6">
        <!-- Peta -->
        <div id="map" class="relative w-full h-[500px] rounded-2xl shadow-2xl" style="z-index: 1;"></div>

        <!-- Filter -->
        <div class="flex flex-wrap gap-3">
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium">Semua</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium">Restoran</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium">Toko Anime</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium">Cafe</button>
            <button class="px-4 py-2 bg-white text-gray-700 rounded-lg shadow-sm hover:shadow transition font-medium">Tempat Nongkrong</button>
        </div>

        <!-- Daftar Lokasi (Cards) -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-6">
            @foreach($places as $place)
                <div class="bg-white rounded-xl shadow hover:shadow-lg transition overflow-hidden">
                    <!-- Gambar Placeholder -->
                    <div class="h-40 bg-gray-200 flex items-center justify-center">
                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 15.75 5.159-5.159a2.25 2.25 0 0 1 3.182 0l5.159 5.159a2.25 2.25 0 0 0 3.182 0l5.159-5.159" />
                        </svg>
                    </div>
                    <div class="p-5">
                        <h3 class="text-lg font-bold text-gray-900">{{ $place->name }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $place->category }}</p>
                        <button class="mt-4 w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 rounded-lg transition">Lihat Rute</button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script>
        var map = L.map('map', { dragging: true, touchZoom: true, scrollWheelZoom: true }).setView([-2.5489, 118.0149], 5);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '&copy; OpenStreetMap contributors'
        }).addTo(map);

        var places = @json($places);
        places.forEach(function(place) {
            let fallbackImg = 'https://images.unsplash.com/photo-1580442531649-6f947385f0cd?q=80&w=400&auto=format&fit=crop';
            let imageUrl = place.image ? '/storage/' + place.image : fallbackImg;

            let popupContent = `
                <div style="font-family: ui-sans-serif, system-ui, sans-serif;">
                    <img src="${imageUrl}" onerror="this.onerror=null; this.src='${fallbackImg}';" style="width: 100%; height: 90px; object-fit: cover; border-radius: 8px; margin-bottom: 8px;">
                    <h3 style="margin: 0 0 4px 0; font-size: 14px; font-weight: bold; color: #1f2937; line-height: 1.2;">${place.name}</h3>
                    <p style="margin: 0 0 6px 0; font-size: 11px; color: #ef4444; font-weight: bold;">🍣 ${place.category}</p>
                    <a href="#" style="display: block; text-align: center; background-color: #3b82f6; color: white; padding: 6px 0; border-radius: 6px; text-decoration: none; font-size: 12px; font-weight: bold;">Lihat Detail &rarr;</a>
                </div>
            `;

            L.marker([place.latitude, place.longitude]).addTo(map).bindPopup(popupContent, {
                maxWidth: 180,
                minWidth: 180
            });
        });
    </script>
</body>
</html>
